<?php

defined('ABSPATH') or die("No script kiddies please!");

class wpbenchmarkio {
	private static $plugin_version = 1;
	private static $talk_to = "https://collect.wpbenchmark.io/tell_me.php";
	
	var $dbtables = array();

	function __construct() {}

	function request_new($m=array()) {
		$m["a"] = "register_new";
		$data = $this->talk($m);

		if (isset($data["bench_code"])) {

			if (!isset($data["expire_in"]))
				$data["expire_in"]=600;

			list($steps, $average_times)=$this->get_initial_steps();

			$settings = get_option("wp-benchmark-io-settings");

			$running_benchmark = array(
				"bench_code"=>$data["bench_code"],
				"progress"=>0,
				"expire_in"=>$data["expire_in"],
				"steps"=>$steps,
				"average_times"=>$average_times,
				"show_on_board"=>$settings["show_on_board"],
				"run_lite_tests"=>$settings["run_lite_tests"]
			);

			$running_benchmark["group_progress"] = $this->get_test_progress($running_benchmark);

			return($running_benchmark);
		} else
			throw new Exception("Empty benchmark ID, try again later.");
	}


	function talk($data) {
		if (isset($_SERVER["REMOTE_ADDR"]))
			$data["wp_user_ip"] = $_SERVER["REMOTE_ADDR"];

		$data["plugin_version"] = self::$plugin_version;

		$response = wp_remote_post(self::$talk_to, array("body"=>$data));
		if( is_wp_error( $response ) ) {
			throw new Exception($response->get_error_message());
		}

		
		return(json_decode(wp_remote_retrieve_body($response),true));
	}



	function run_next($bench, $skip_next=false) {

		$bench["executed_description"] = "Unknown error occured";

		$steps_total = 0;
		foreach($bench["steps"] as $group_key=>$group_data) {
			$steps_total+=count($group_data["run_tests"]);
		}


		
		if ($steps_total==0) {
			$bench["progress"]=100;
			return($bench);
		}

		$steps_completed=0;
		$step_to_run_found=false;
		$step_to_run_key  =null;
		$step_group_to_run=null;

		foreach($bench["steps"] as $group_key=>$group_data) {

			if ($step_to_run_found===false) {

				foreach($group_data["run_tests"] as $sk=>$sv) {
					if ($sv["is_complete"]===true)
						$steps_completed++;

					if ($step_to_run_found===false && $sv["is_complete"]===false) {
						$step_to_run_key = $sk;
						$step_to_run_found = true;
						$step_group_to_run = $group_key;
					} 
				}
			}

		}


		if ($step_to_run_found) {

			$function_name = $bench["steps"][$step_group_to_run]["run_tests"][$step_to_run_key]["function"];

			if ($skip_next) {
				// skipping this failed test
				$time_spent = -1;

				$bench["average_times"][$function_name]["times_run"]++;
				$bench["average_times"][$function_name]["total_time"] = -1;
			} else {

				$start_time = microtime(true);
				$this->$function_name();
				$time_spent = microtime(true)-$start_time;

				

				$time_spent=(int)($time_spent*100000);
				$time_spent=$time_spent/100;

				$bench["average_times"][$function_name]["times_run"]++;
				$bench["average_times"][$function_name]["total_time"]+=$time_spent;

			}


			$steps_completed++;
			$bench["steps"][$step_group_to_run]["run_tests"][$step_to_run_key]["is_complete"]=true;
			$bench["steps"][$step_group_to_run]["run_tests"][$step_to_run_key]["measured_time"]=$time_spent;
			$bench["executed_description"]=$bench["steps"][$step_to_run_key]["name"] . ": ".$bench["steps"][$step_to_run_key]["measured_time"]."ms";

		}

		if ($steps_completed==$steps_total) {
			$bench["progress"]=100;
		} else {
			$bench["progress"]=(int)(($steps_completed/$steps_total)*100);
		}

		$bench["group_progress"] = $this->get_test_progress($bench);

		return($bench);
	}


	function calculate_finals($bench) {


		$this->clean_tmp_folder();
		$this->clean_db_test_tables();

		$dsc = "";
		foreach($bench["average_times"] as $fn=>$fd) {
			$bench["average_times"][$fn]["average_time"]=round($fd["total_time"]/$fd["times_run"]);
		 	# $dsc .= $fn.": ".$bench["average_times"][$fn]["average_time"]."ms<br>";
		}
		# executed_description

		$site_name = get_bloginfo("name");

		# post results to central database and get averages
		$our_results = array(
			"a"=>"store_results",
			"bench_code"=>$bench["bench_code"],
			"results"=>$bench["average_times"],
			"site_url"=>get_site_url(),
			"site_title"=>$site_name,
			"php_version"=>phpversion(),
			"show_on_board"=>$bench["show_on_board"],
			"run_lite_tests"=>$bench["run_lite_tests"]	
		);

		$global_averages = $this->talk($our_results);


		$bench["executed_description"] = $dsc;
		$bench["group_progress"] = $this->get_test_progress($bench);

		if (isset($global_averages["thankyou"]) && isset($global_averages["my_score"])) {
			# $bench["global_averages"] = $global_averages["averages"];

			list($function_types, $functions_to_run) = $this->get_function_defs();


			$my_results = array();
			foreach($global_averages["my_score"] as $test_function=>$test_score) {

				foreach($functions_to_run as $func_def) {
					if ($func_def["function"]==$test_function) {
						$my_results[$test_function]=array("title"=>$func_def["name"], "url"=>$func_def["url"], "test_score"=>$test_score, "score_ratio"=>$func_def["test_ratio"]);
					}
				}

				
			}


			$host_score = 0;
			$score_elements = 0;

			$dsc = "<div class='wpio-result-container'>";

			foreach($function_types as $ftype=>$ftype_def) {

				$dsc .= "
				<div class='wpio-flex-row wpio-fntype-row'>
					<div class='wpio-flex-col wpio-type-col'>
						".$ftype_def["name"]."
					</div>
					<div class='wpio-flex-col'>
				";


				foreach($functions_to_run as $func_def) {
					if ($func_def["type"]==$ftype) {
						
						$f_score = $my_results[$func_def["function"]]["test_score"];
						$f_ratio = $my_results[$func_def["function"]]["score_ratio"];

						$host_score += $f_score*$f_ratio;
						$score_elements += $f_ratio;

						if ($f_score<2)
							$row_class = "wpio-col-score02";
						else if ($f_score<5)
							$row_class = "wpio-col-score25";
						else if ($f_score<6)
							$row_class = "wpio-col-score56";
						else if ($f_score<7)
							$row_class = "wpio-col-score67";
						else if ($f_score<8)
							$row_class = "wpio-col-score78";
						else if ($f_score<9)
							$row_class = "wpio-col-score89";
						else
							$row_class = "wpio-col-score910";

						$dsc .= "
							<div class='wpio-flex-row'>
								<div class='wpio-flex-col wpio-text-black'>".$my_results[$func_def["function"]]["title"]."</div>
								<div class='wpio-flex-col wpio-score-col ".$row_class."'>".$f_score."</div>
							</div>
						";
					}
				}

				$dsc .= "
					</div>
				</div>";		
			}


			#$total_score = round(($host_score/$score_elements)*10)/10;
			$total_score = $global_averages["my_score"]["total"];
			#$dsc .= "<tr><td colspan=2 class='total-score'></td></tr>";

			if ($total_score<2)
				$row_class = "wpio-col-score02";
			else if ($total_score<5)
				$row_class = "wpio-col-score25";
			else if ($total_score<6)
				$row_class = "wpio-col-score56";
			else if ($total_score<7)
				$row_class = "wpio-col-score67";
			else if ($total_score<8)
				$row_class = "wpio-col-score78";
			else if ($total_score<9)
				$row_class = "wpio-col-score89";
			else
				$row_class = "wpio-col-score910";

			$dsc .= "
				<div class='wpio-flex-row'>
					<div class='wpio-flex-col col-read-more'><a href='https://report.wpbenchmark.io/" . $bench["bench_code"] . "/' target=_blank>Read more</a></div>
					<div class='wpio-flex-col total-score'>Your server score</div>
					<div class='wpio-flex-col total-score-markcol ".$row_class."'>".$total_score."</div>
				</div>
			";

			$dsc .= "</div> <!-- end flex container -->";
			# $dsc .= "</table>";

			$bench["executed_description"] = $dsc;
			$bench["total_score"]=$total_score;
		} else {
			$bench["global_averages"] = array();
			$bench["executed_description"].="<span style='color:red;'>Failed to load global averages!</span>";
		}

		return($bench);
	}


	function get_function_defs() {
		$function_types = array(
			"cpu_memory"=>array("name"=>"CPU &amp; Memory", "progress"=>0, "run_tests"=>array()),
			"filesystem"=>array("name"=>"Filesystem", "progress"=>0, "run_tests"=>array()),
			"database"=>array("name"=>"Database", "progress"=>0, "run_tests"=>array()),
			"network"=>array("name"=>"Network", "progress"=>0, "run_tests"=>array())
		);

		
		$functions_to_run = array();
	
		$functions_to_run[] = array("type"=>"cpu_memory", "function"=>"test_cpu_regex", "name"=>"Operations with large text data", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/random-string-operations/", "test_ratio"=>2);
		$functions_to_run[] = array("type"=>"cpu_memory", "function"=>"test_cpu_randbytes", "name"=>"Random binary data operations", "description"=>"Test CPU and memory with random binary data generation and memory prefill", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/random-binary-data/", "test_ratio"=>1);

		
		$functions_to_run[] = array("type"=>"filesystem", "function"=>"test_filewrite", "name"=>"Filesystem write ability", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/filesystem-write-speed/", "test_ratio"=>3);
		$functions_to_run[] = array("type"=>"filesystem", "function"=>"test_filecopy", "name"=>"Local file copy and access speed", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/copying-and-reading-files/", "test_ratio"=>2);
		$functions_to_run[] = array("type"=>"filesystem", "function"=>"test_filewrite_smallfiles", "name"=>"Small file IO test", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/filesystem-write-speed/", "test_ratio"=>3);



		$functions_to_run[] = array("type"=>"database", "function"=>"test_db_insert", "name"=>"Importing large amount of data to database", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/database-data-import/", "test_ratio"=>2);
		$functions_to_run[] = array("type"=>"database", "function"=>"test_db_simple", "name"=>"Simple queries on single table", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/accessing-database-data/", "test_ratio"=>3);
		$functions_to_run[] = array("type"=>"database", "function"=>"test_db_joins", "name"=>"Complex database queries on multiple tables", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/complex-database-queries/", "test_ratio"=>4);


		$functions_to_run[] = array("type"=>"network", "function"=>"test_network_download", "name"=>"Network download speed test", "description"=>"", "is_complete"=>false, "measured_time"=>0, "result_dsc"=>"Not run", "url"=>"/wordpress-test/network-download-speedtest/", "test_ratio"=>2);


		return(array($function_types, $functions_to_run));
	}




	function get_initial_steps() {

		
		list($function_types, $functions_to_run) = $this->get_function_defs();

		# "test_cpu_md5", "test_cpu_rand", "test_memory_array");

		# if (self::$settings)
		# run_lite_tests
		$settings = get_option("wp-benchmark-io-settings");
		if ($settings["run_lite_tests"]==1)
			$times_to_run=1;
		else
			$times_to_run = 5;

		$steps = array();
		$average_times = array();

		foreach($functions_to_run as $fn) {
			$average_times[$fn["function"]]=array("times_run"=>0, "total_time"=>0);

			for($i=0;$i<$times_to_run;$i++) {
				# $steps[] = $fn;			
				$function_types[$fn["type"]]["run_tests"][]=$fn;
			}
		}

		$this->clean_tmp_folder();

		return(array($function_types, $average_times));
	}

	function random_string($len=10) {
		$avail_chars = '1234567890ABCDEFGHJIKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$char_len=strlen($avail_chars);
		$rand_string = "";
		for($sid_n=0; $sid_n < $len; $sid_n++) {
			$rand_string .= substr($avail_chars, rand(0, $char_len-1), 1);
		}

		return($rand_string);
	}


	function get_test_progress($bench) {
		$p = array();

		foreach($bench["steps"] as $group_key=>$group_data) {
			$group_complete = 0;
			$group_tests    = count($group_data["run_tests"]);

			foreach($group_data["run_tests"] as $t) {
				if ($t["is_complete"])
					$group_complete++;
			}

			$p[$group_key]=array("key"=>$group_key, "name"=>$group_data["name"], "group_progress"=>( ((int)(($group_complete/$group_tests)*100)) ));
		}

		return($p);
	}




	function test_cpu_md5() {
		# usleep(100);
		for ($i=0;$i<2000000;$i++) {
			$q = md5(random_string(1024));
		}
	}

	function test_cpu_rand() {
		for ($i=0;$i<1000000;$i++) {
			$b = rand(0,1000000);
		}
		for ($i=0;$i<1000000;$i++) {
			$b = rand(0,1000000);
		}
		for ($i=0;$i<1000000;$i++) {
			$b = rand(0,1000000);
		}
		for ($i=0;$i<1000000;$i++) {
			$b = rand(0,1000000);
		}
		for ($i=0;$i<1000000;$i++) {
			$b = rand(0,1000000);
		}
	}
	function test_cpu_randbytes() {

		$a=null;
		for($i=0;$i<1000;$i++) {
			$a .= $this->random_string(10240);
		}

		$ahex = bin2hex($a);
		$ahex_capital = "";

		for ($s=0;$s<strlen($ahex);$s++) {
			$ahex_capital.=md5(strtoupper($ahex[$s]));

			if (strlen($ahex_capital)>1024000)
				$ahex_capital="";
		}

	}

	function test_cpu_regex() {
		$data = array();

		for ($i=0;$i<100;$i++) {
			$s = "";

			while(mb_strlen($s)<20480) {
				$s.=$this->random_string(10).", ";				
			}

			$data[] = $s;
		}

		$data_splitted_2 = array();
		$data_splitted = array();
		foreach($data as $big_string) {
			$data_splitted[]=explode(",", $big_string);
			array_merge($data_splitted_2, preg_split("/[\s,]+/", $big_string));
		}

		foreach($data_splitted as $rk=>$r_array) {
			sort($data_splitted[$rk]);
		}


		foreach($data_splitted_2 as $rk=>$rv) {
			$data_splitted_2[$rk]=md5(md5($rv));
		}

		sort($data_splitted_2);

		foreach($data_splitted as $rk=>$r_array) {
			foreach($r_array as $sk=>$sv) {
				$data_splitted[$rk][$sk]=md5($sv);
			}
		}

		unset($data);
	}





	function test_memory_array() {
		for($i=0;$i<100;$i++) {
			$data = array();

			for($j=0;$j<50;$j++) {
				$row=array();
				for($k=0;$k<40;$k++) {
					$s="";
					for($u=0;$u<30;$u++)
						$s.="ssdifjoi oiwejfoweijfwe ewifjewfefjewfijweoifjweofijewofijweofijewssdifjoi oiwejfoweijfwe ewifjewfefjewfijweoifjweofijewofijweofijewssdifjoi oiwejfoweijfwe ewifjewfefjewfijweoifjweofijewofijweofijewssdifjoi oiwejfoweijfwe ewifjewfefjewfijweoifjweofijewofijweofijewssdifjoi oiwejfoweijfwe ewifjewfefjewfijweoifjweofijewofijweofijew";
					$row[]=$s;					
				}

				$data[]=$row;
			}

			unset($data);
		}
	}
	function test_cpu_memory() {
		$data=array();
		for ($i=0;$i<200000;$i++) {
			$data[$i]=rand(1,5000);
		}

		foreach($data as $dk=>$dv) {
			$data[$dk]=md5(serialize($dv));
		}

		for ($i=0;$i<5000;$i++) {
			$data[(rand(1,100000))] = md5(md5(rand(1,100000)));
		}

		for ($i=0;$i<100000;$i++) {
			$data[$i]=rand(1,5000);
		}

		foreach($data as $dk=>$dv) {
			$data[$dk]=md5(serialize($dv));
		}

		for ($i=0;$i<5000;$i++) {
			$data[(rand(1,100000))] = md5(md5(rand(1,100000)));
		}

		for ($i=0;$i<100000;$i++) {
			$data[$i]=rand(1,5000);
		}

		foreach($data as $dk=>$dv) {
			$data[$dk]=md5(serialize($dv));
		}

		for ($i=0;$i<5000;$i++) {
			$data[(rand(1,100000))] = md5(md5(rand(1,100000)));
		}
	}


	function test_filewrite() {
		$tmp_folder = $this->tmp_folder_name();
		#$this->clean_tmp_folder();

		$generated_filenames = array();

		$write_content = "";
		#for($k=0;$k<20;$k++) {
			# make it 1mb
			for ($m=0;$m<1024;$m++) 
				$write_content .= $this->get_1kb_text();
		#}

		$fn = $tmp_folder."/tmp.filewrite";

		for($i=0;$i<20;$i++) {
			
			# $fn = $tmp_folder."/tmp.filewrite.".rand(1000,10000);
			# $generated_filenames[]=$fn;

			if (file_exists($fn)) {
				unlink($fn);
				clearstatcache();
			}

			$fp = fopen($fn, "w");

			# let's write 50mbytes
			for ($k=0;$k<50;$k++)
				fwrite($fp, $write_content);


			fclose($fp);

			clearstatcache();
			#unlink($fn);


			# randomly delete some of the files, so we dont consume too much disk space
			#if (rand(0,2)==1) {
			#	$randomly_selected_file = $this->random_file_from_tmp();			
			#	if ($randomly_selected_file!="")
			#		unlink($randomly_selected_file);
			#}
		}

		unset($write_content);
	}
	function test_filewrite_smallfiles() {
		$tmp_folder = $this->tmp_folder_name();
		#$this->clean_tmp_folder();

		$generated_filenames = array();

		$write_content = "";
		# make it 150kb
		for ($m=0;$m<150;$m++) 
			$write_content .= $this->get_1kb_text();
		
		#$fn = $tmp_folder."/tmp.smallfile";

		for($i=0;$i<500;$i++) {
			
			# 
			$fn = $tmp_folder."/tmp.filewrite.".rand(1000,10000);
			#$generated_filenames[]=$fn;

			$fp = fopen($fn, "w");
			fwrite($fp, $write_content);
			fclose($fp);
			clearstatcache();
			
			# unlink($fn);
		}

		unset($write_content);
	}


	function test_filecopy() {

		$tmp_folder = $this->tmp_folder_name();
		# $randomly_selected_file = $this->random_file_from_tmp();
		$source_fn = $tmp_folder."/tmp.filewrite";


		$copy_count = 20;

		if (!file_exists($source_fn)) {
			$write_content = "";
			#for($k=0;$k<50;$k++) {
				# make it 1mb
				for ($m=0;$m<1024;$m++) 
					$write_content .= $this->get_1kb_text();
			#}

			
			$fp = fopen($source_fn, "w");
			
			for($k=0;$k<50;$k++) {
				fwrite($fp, $write_content);
			}

			fclose($fp);

			$copy_count--;
		}


		

		for ($i=0;$i<$copy_count;$i++) {

			clearstatcache();
			$dest_fn = $tmp_folder."/tmp.filecopy.".rand(10000,99999);
			

			# find random file in temp folder
			#$randomly_selected_file = $this->random_file_from_tmp();
			#$file_content = file_get_contents($randomly_selected_file);
			#unset($file_content);

			# find random file in temp folder
			# $randomly_selected_file = $this->random_file_from_tmp();

			#if ($randomly_selected_file=="")
			#	throw new Exception("Failed to select test file from temporary folder!");
			
			copy($source_fn, $dest_fn);

			clearstatcache();
			unlink($dest_fn);
			
			#$randomly_selected_file = $this->random_file_from_tmp();			
			#if ($randomly_selected_file!="")
			#	unlink($randomly_selected_file);
		}
	}


	function random_file_from_tmp() {
		$tmp_folder=$this->tmp_folder_name();

		if (!is_dir($tmp_folder)) {
			throw new Exception("Something went wrong, TMP folder does not exist. Please restart the benchmark!");
		}

		$tmp_files = array();
		if ($dh=opendir($tmp_folder)) {
			while(($file=readdir($dh))!==false) {
				if ($file!="." && $file!="..") {
					if (is_file($tmp_folder."/".$file))
						$tmp_files[]=$tmp_folder."/".$file;
				}
			}
			closedir($dh);
		}

		$found_files = count($tmp_files);
		if ($found_files==0)
			throw new Exception("Something went wrong, could not find any files in TMP folder. Please restart the benchmark!");

		return($tmp_files[rand(0,($found_files-1))]);
	}

	function tmp_folder_name() {
		return(dirname(__FILE__)."/tmp");
	}
	function make_tmp_folder() {
		$tmp_folder = $this->tmp_folder_name();
		if (!is_dir($tmp_folder)) {
			if (!mkdir($tmp_folder))
				throw new Exception("Failed to create temporary folder, please check that PHP can create ".$tmp_folder."!");
		}
	}
	function clean_tmp_folder() {
		$tmp_folder=$this->tmp_folder_name();

		if (!is_dir($tmp_folder)) {
			$this->make_tmp_folder();
			return true;
		}
		
		if ($dh=opendir($tmp_folder)) {
			while(($file=readdir($dh))!==false) {
				if ($file!="." && $file!="..") {
					if (is_file($tmp_folder."/".$file))
						unlink($tmp_folder."/".$file);
				}
			}
			closedir($dh);
		}
	}

	function init_dbtable_names() {
		global $wpdb;
		$this->dbtables=$this->get_db_table_names();
	}

	function get_db_table_names() {
		global $wpdb;

		$tables = array();
		$tables["obj"] = $wpdb->prefix."wpbench_o";
		$tables["prop"] = $wpdb->prefix."wpbench_p";
		$tables["log"] = $wpdb->prefix."wpbench_l";		

		return($tables);
	}

	function clean_db_test_tables() {
		global $wpdb;

		$this->init_dbtable_names();

		# $dbtables = $this->get_db_table_names();
		foreach($this->dbtables as $dbt) {
			$wpdb->query("DROP TABLE IF EXISTS ".$dbt.";");
		}

		return true;
	}

	function create_db_test_tables() {
		global $wpdb;

		$this->init_dbtable_names();

		$sql = "CREATE TABLE IF NOT EXISTS `".$this->dbtables["obj"]."` (
			  `o_id` int(10) unsigned NOT NULL,
			  `random_int` int(10) unsigned NOT NULL,
			  `object_name` varchar(255) NOT NULL,
			  `object_type` enum('ocean','mountain','space','earth') NOT NULL
			) ;
		";
		$wpdb->query($sql);

		$sql = "ALTER TABLE `".$this->dbtables["obj"]."` ADD PRIMARY KEY (`o_id`), ADD KEY `object_type` (`object_type`);";
		$wpdb->query($sql);

		$sql = "ALTER TABLE `".$this->dbtables["obj"]."` MODIFY `o_id` int(10) unsigned NOT NULL AUTO_INCREMENT;";
		$wpdb->query($sql);



		$sql = "CREATE TABLE IF NOT EXISTS `".$this->dbtables["prop"]."` (
		  `p_id` bigint(20) unsigned NOT NULL,
		  `o_id` int(10) unsigned NOT NULL,
		  `p_name` varchar(255) NOT NULL,
		  `p_data` mediumblob 
		) ;
		";
		$wpdb->query($sql);

		$sql = "ALTER TABLE `".$this->dbtables["prop"]."` ADD PRIMARY KEY (`p_id`), ADD KEY `o_id` (`o_id`);";
		$wpdb->query($sql);

		$sql = "ALTER TABLE `".$this->dbtables["prop"]."` MODIFY `p_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;";
		$wpdb->query($sql);


		$sql = "
		CREATE TABLE IF NOT EXISTS `".$this->dbtables["log"]."` (
		  `l_id` bigint(20) unsigned NOT NULL,
		  `o_id` int(10) unsigned NOT NULL,
		  `p_id` bigint(20) unsigned NOT NULL,
		  `txt` varchar(255) NOT NULL
		) ;";
		$wpdb->query($sql);
		
		$sql="ALTER TABLE `".$this->dbtables["log"]."` ADD PRIMARY KEY (`l_id`), ADD KEY `o_id` (`o_id`), ADD KEY `p_id` (`p_id`), ADD KEY `o_id_2` (`o_id`,`p_id`);";
		$wpdb->query($sql);

		$sql="ALTER TABLE `".$this->dbtables["log"]."` MODIFY `l_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;";
		$wpdb->query($sql);

		return true;
	}

	function insert_into_db_testlog($txt, $o_id, $p_id=0) {
		global $wpdb;

		$wpdb->insert($this->dbtables["log"], array(
				"o_id"=>$o_id,
				"p_id"=>$p_id,
				"txt"=>$txt
			)
		);
	}

	function test_db_insert() {
		global $wpdb;

		$this->init_dbtable_names();

		$this->clean_db_test_tables();

		# create test tables
		$this->create_db_test_tables();

		$object_types = array("ocean", "mountain", "space", "earth");
		$object_properties = array("name", "size", "value", "data1", "data2");

		# generate 10 random strings to use
		$random_data = array();
		$random_binary = "";
		for ($j=0;$j<10;$j++) {
			$random_binary = "";
			
			for($r=0;$r<30;$r++) {
				$random_binary.=$this->random_string(1024);
			}
			$random_data[$j]=$random_binary;
		}

		$next_o_type = 0;

		for($o=1;$o<=500;$o++) {
			$wpdb->insert(
				$this->dbtables["obj"],
				array(
					"random_int"=>rand(1,1000),
					"object_name"=>"Object ".$o,
					"object_type"=>$object_types[$next_o_type]
				)
			);
			$o_id = $wpdb->insert_id;

			$this->insert_into_db_testlog("Created new object, nr ".$o, $o_id);

			foreach($object_properties as $p) {
				$wpdb->insert(
					$this->dbtables["prop"],
					array(
						"o_id"=>$o_id,
						"p_name"=>$p
					)
				);
				$p_id = $wpdb->insert_id;

				$this->insert_into_db_testlog("Created property ".$p." for o_id=".$o_id, $o_id, $p_id);

				if ($p=="name") {

					$tmp_data = $wpdb->get_results("select object_name from ".$this->dbtables["obj"]." where o_id=".$o_id.";", ARRAY_A );

					if (count($tmp_data)>0) {
						$set_value = $tmp_data[0]["object_name"];
					} else {
						$set_value = "uknown name";
					}

				} else if ($p=="data1" || $p=="data2") {
					
					$set_value = $random_data[($o%10)];

				} else {
					$set_value = rand(10,1000);
				}

				$wpdb->update($this->dbtables["prop"], array("p_data"=>$set_value), array("p_id"=>$p_id));
				$this->insert_into_db_testlog("Updated property ".$p." with a value", $o_id, $p_id);
			}

			$next_o_type++;
			if ($next_o_type>3)
				$next_o_type=0;
		}
	}


	function test_db_simple() {
		global $wpdb;
		$this->init_dbtable_names();

		$random_data = $wpdb->get_results("select * from ".$this->dbtables["obj"]." where random_int>=10 and random_int<=990 order by RAND() limit 600;", ARRAY_A );
		foreach($random_data as $r) {
			$o_properties = $wpdb->get_results("select * from ".$this->dbtables["prop"]." where o_id=".$r["o_id"].";", ARRAY_A );						
		}
		foreach($random_data as $r) {
			$o_properties = $wpdb->get_results("select * from ".$this->dbtables["prop"]." where o_id=".$r["o_id"].";", ARRAY_A );						
		}

		# now let's do something about query cache
		$full_data = $wpdb->get_results("select * from ".$this->dbtables["obj"]." order by RAND()", ARRAY_A );
		foreach($full_data as $r) {
			$wpdb->query("delete from ".$this->dbtables["prop"]." where o_id=".$r["o_id"]." and p_name='data2';");			
			$this->insert_into_db_testlog("Deleted data2 property for object ".$r["o_id"], $r["o_id"]);

			$o_properties = $wpdb->get_results("select * from ".$this->dbtables["prop"]." where o_id=".$r["o_id"].";", ARRAY_A );						

			foreach($o_properties as $p) {
				if ($p["p_name"]=="size") {
					$wpdb->query("delete from ".$this->dbtables["log"]." where p_id=".$p["p_id"].";");
					$wpdb->update($this->dbtables["prop"], array("p_data"=>rand(200,2000)), array("p_id"=>$p["p_id"]));
					$this->insert_into_db_testlog("Update data2 property for object ".$r["o_id"], $r["o_id"], $p["p_id"]);
				}
			}

			$tmp_properties = $wpdb->get_results("select * from ".$this->dbtables["prop"]." where o_id=".$r["o_id"].";", ARRAY_A );	

			$wpdb->update($this->dbtables["log"], array("txt"=>"Reset this value for testing.. Thank you for checking this out!"), array("o_id"=>$r["o_id"]));

		}
	}


	function test_db_joins() {
		global $wpdb;

		$this->init_dbtable_names();

				
		for ($i=0;$i<50;$i++) {
			#$wpdb->get_results("select ".$this->dbtables["obj"].".o_id, ".$this->dbtables["prop"].".p_name, ".$this->dbtables["log"].".txt from ".$this->dbtables["obj"]." left join ".$this->dbtables["prop"]." on ".$this->dbtables["obj"].".o_id=".$this->dbtables["prop"].".o_id left join ".$this->dbtables["log"]." on ".$this->dbtables["prop"].".p_id=".$this->dbtables["log"].".p_id where ".$this->dbtables["obj"].".o_id=".rand(1,999)." and ".$this->dbtables["prop"].".p_name='name'");

			
			$sql = "select ".$this->dbtables["obj"].".o_id, ".$this->dbtables["prop"].".p_name, ".$this->dbtables["log"].".txt from ".$this->dbtables["obj"]." left join ".$this->dbtables["prop"]." on ".$this->dbtables["obj"].".o_id=".$this->dbtables["prop"].".o_id left join ".$this->dbtables["log"]." on ".$this->dbtables["prop"].".p_id=".$this->dbtables["log"].".p_id where ".$this->dbtables["prop"].".p_name like '%".$this->random_string(2)."%' order by RAND() limit 1;";
			$wpdb->get_results($sql);
		}


		for ($i=0;$i<50;$i++) {
			$sql = "select ".$this->dbtables["prop"].".p_id, ".$this->dbtables["prop"].".p_name, ".$this->dbtables["log"].".txt from ".$this->dbtables["log"]." left join ".$this->dbtables["prop"]." on ".$this->dbtables["log"].".p_id=".$this->dbtables["prop"].".p_id where ".$this->dbtables["prop"].".p_data like '%".$i."%' order by ".$this->dbtables["log"].".txt, ".$this->dbtables["prop"].".p_id desc limit 50;";
			$wpdb->get_results($sql);
		}

	}


	function test_network_download() {
		$tmp_folder = $this->tmp_folder_name();

		$test_filename = "download_test.jpg";
		$destination_filename = $tmp_folder."/".$test_filename;

		$download_args = array(
			"stream"=>true,
			"filename"=>$tmp_folder."/".$test_filename
		);

		for($i=0;$i<5;$i++) {
			if (file_exists($tmp_folder."/".$test_filename))
				unlink($tmp_folder."/".$test_filename);

			wp_remote_get("https://bandwidth-test.wpbenchmark.io/".$test_filename, $download_args);
		}


		if (file_exists($tmp_folder."/".$test_filename))
			unlink($tmp_folder."/".$test_filename);
	}

	function get_1kb_text() {
		return("oooOoOoOOOOOOooooOoooOooOOoOOOooOOoooOOoOoOOoooOOOoOOoOoOoOooOOOOOoooooooOOoOOOoooOOoooooOOOooOoooOOoOoOooOOooOoOOOOoOOooooOoooOoOOOOOOooOOooooOoOoOooOOOOoOoOooOoOooooOooOOOoOOOoOOoooOOooOooOOooOOoooOooOoOOoOOOOOOoOooOoOOOoOOoOOoOoOooooOOOoOoOoOoOooooOoOOooOOoooOoOoooOoOOOoOooooOooOOoOooOOOoOOOooOOOOOOOOoooooOoOOoOooOOoOoooooOooooooOooooOOooooOOoOooooooOooOoOOoOOooOooOOoOooOoooOoOoOOoOOOooOooooOOoOoOooooOooOoOoOOoOoOoOooOOOooOOoooOoooooOooOoOoOoOOoooOOOoOOooOOooOoOOOoOOOooOooooOOOOoOooOOooOoOOooooOooOOOoOoooOOooOOoOooOooOOoOOOooOoooOOoOoOOOoOoOOOOoOoOOOOoooooOoOOOooOOoOoOoOOOOooooOooOOooooOooOoooooooOOooooOooOooOoOoOooooooOOooOOOooooOooooOOooOOOOOOoOooOOOoOOoOooOoOOOOOoOoOoOooOOOOOOoOoOOOoooOooOoOoOOOoOOOOOoooooOoOoOOooooooOoOoOOOoOooOooooOOOooooOoOOoOOOoOoOoOoOooOOOooOoOooOoOOOooOoOoOoOOOOOoOOoOoOoOooOoOoOOOOoOOOOoOoooOoOOOOooOOOoOOOOOOOOooOooOOOooOooooOooOooOOoOooOOooOOOOoOOoOOOooOoOoOOOOOoooOoOoOoOoooooooOOOOooOOOOooOOOOoOooooOOooOoOoOoOoOOoOoooooooOOOoOOoOOooOoooOooOOOooOOoOoooooOoOoooOooOOooOoOOOoooOOOoO");
	}
}
