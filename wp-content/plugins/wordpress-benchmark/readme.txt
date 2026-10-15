=== WordPress Hosting Benchmark tool ===
Contributors: anton.aleksandrov
Donate link: https://wpbenchmark.io/
Tags: benchmark, report, test, speed, fast, mysql, hosting
Requires at least: 4.0
Tested up to: 5.8
Requires PHP: 5.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Benchmark your hosting server CPU, memory and disk, compare with others.

== Description ==

According to kinsta.com research, Wordpress has become the most popular CMS system in the world. Big and small websites use it. People build complex webshops, galleries, booking systems, forums and corporate pages using it. Thousands of developers code for Wordpress every day and success of many projects rely on information and data stored and processed by it.

And every Wordpress installation run on a server, hosted somewhere around the globe. Server can be physical or virtual, it might run Unix, Linux, Windows or other OS, might have different processor, memory and storage hardware. Some are more powerful and some are less powerful, but most users never see these computers in real live.

The speed of your Wordpress depends on many factors. Number of plugins, logic complexity and data amount are those, which impact most of all. But what about raw server power? This project attempts to answer this question. Plugin, that we have developed will run unified tests and measure their execution times. Based on that we will score your server performance, so you can decide, if that is enough for your project or not.

At this stage, May 2019, we are launching this project as beta-version. Your comments and suggestions are kindly welcome @ info@wpbenchmark.io


== Frequently Asked Questions ==
Q: What is being tested?
A: CPU, memory bandwidth, disk speed, network download speed.

Q: What should be our considerations, before using this plugin?
A: Plugin will generate large temporary files and run a lot SQL queries. You should make sure, your hosting has at least 500MB of free disk space and that your database server allows large number of queries. All temporary files and database tables will be deleted after benchmark is complete.

== Installation ==

Please use default Wordpress plugin installation method through Wordpress plugin repository.


== Screenshots ==
1. https://wpbenchmark.io/screenshots/wordpress-benchmark-hosting-example-1.jpg


== Changelog ==
1.0.1  - disk benchmark fix, added workaround to skip failed tests.

0.9.3 - small adjustements on filesystem tests

0.9  - reviewed testing policy, several tests have been made lighter, so those can now be run at servers with restrictions.

0.8  - removed force selection of MyISAM for database tests. Now the default database engine will be used.

0.7  - added option to run each test just once, instead of 5 times. + minor fixes

0.6  - minor fix and Wordpress version compatability update

0.5  - added history of executed benchmarks

0.4  - minor polishing and WP version compatability check

0.3  - completely rewritten filesystem benchmark tests.

0.23 - small fix in filesystem benchmark tests.

0.22 - small fixes for really low resource servers.

0.21 - added new test - filesystem benchmark writing many small files, adjusted CPU tests

0.2 - Small changes in test routines

0.1 - Initial version
