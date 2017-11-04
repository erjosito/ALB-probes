<html>
   <header>
     <title>Advanced probing for Azure Load Balancers</title>
   </header>
   <body>
     <h1>
       Welcome to the Lab on Advanced Healthchecks with Azure Load Balancer
     </h1>
     <br>
     <?php
         $reachabilityTest = False;
         $daemonTest = True;
     ?>
     <?php
        // Reachability test
        if ($reachabilityTest) {
            print ("<h2>Reachability Test</h2>");
            $hosts = array ("bing.com", "google.com");
            $allReachable = true;
            foreach ($hosts as $host) {
              $result = exec ("ping -c 1 -W 1 " . $host . " 2>&1 | grep received");
              $pos = strpos ($result, "1 received");
              if ($pos === false) {
                $allReachable = false;
                break;
              }
            }
            if ($allReachable === false) {
              // Ping did not work
              http_response_code (299);
              print ("At least one target host does not seem to be reachable (" . $host . ")\n");
            } else {
              // Ping did work
              http_response_code (200);
              print ("All target hosts seem to be reachable\n");
            }
        }
     ?>

     <?php
        // Daemon test
        if ($daemonTest) {
            print ("<h2>Daemon Test</h2>");
            $daemons = array ("httpd", "sshd");
            $allRunning = true;
            foreach ($daemon as $daemons) {
              $result = exec ("systemctl status " . $daemon . " 2>&1 | grep 'active (running)'");
              $pos = strpos ($result, "running");
              if ($pos === false) {
                $allRunning = false;
                break;
              }
            }
            if ($allRunning === false) {
              // Daemon not running
              http_response_code (298);
              print ("At least one daemon does not seem to be running (" . $daemon . ")\n");
            } else {
              // All daemons running
              http_response_code (200);
              print ("All daemons seem to be running\n");
            }
        }
     ?>

   </body>
</html>
