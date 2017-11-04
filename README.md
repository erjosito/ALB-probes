# PHP page to use with advanced Azure LB health checks

As you probably know, Azure LB can leverage advanced healthchecks, that are deemed to be successful only if the return code is exactly 200 (not 201 or 202). Consequently you can poll a Web page that will do a couple of tests, and return 200 only if those tests are successful.

In this repository you find an example of such a web page. The tests are used with PHP, and other than the Web server and PHP itself, the only prerequisite is having nmap installed in the system (for the TCP port test). You can configure which tests to make in the variables at the beginning, the options are:

- Host reachability: a 200 will be returned only if all specified hosts can be reached (via ICMP)
- Daemon state: a 200 will be returned only if all specified daemons are up (state verified with systemctl status)
- TCP port open: a 200 will be returned only if all specified TCP ports are open in the local machine (verified with nmap)
