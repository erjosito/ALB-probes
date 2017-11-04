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
         $reachabilityTest = True;
         $daemonTest = True;
         $localTCPTest = True;
         $localUDPTest = True;
         ?>
     <?php
        // Reachability test
        if ($reachabilityTest === True) {
            print ("<h2>Reachability Test</h2>\n");
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
              print ("        At least one target host does not seem to be reachable (" . $host . ")\n");
            } else {
              // Ping did work
              http_response_code (200);
              print ("        All target hosts seem to be reachable\n");
            }
        }
     ?>

     <?php
        // Daemon test
        if ($daemonTest === True) {
            print ("<h2>Daemon Test</h2>\n");
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
              print ("        At least one daemon does not seem to be running (" . $daemon . ")\n");
            } else {
              // All daemons running
              http_response_code (200);
              print ("        All daemons seem to be running\n");
            }
        }
     ?>

     <?php
        // Local TCP Port Test
        if ($localTCPTest === True) {
            print ("<h2>Local TCP Port Test - nmap needs to be installed</h2>\n");
            // Check nmap is installed
            $nmapPath = exec ("which nmap 2>/dev/null");
            if ( strlen ($nmapPath) > 0 ) { 
                $ports = array ("22", "80");
                $allOpen = true;
                print ("        <ul>");
                foreach ($port as $ports) {
                    $result = exec ($nmapPath . " localhost -p " . $port . " | grep tcp");
                    print ("        <li>" . $result . "</li>\n");
                    $pos = strpos ($result, "open");
                    if ($pos === False) {
                        $allOpen = false;
                        break;
                    }
                  }
                print ("        </ul>");
                if ($allOpen === false) {
                    // Daemon not running
                    http_response_code (298);
                    print ("        At least one TCP port does not seem to be open (" . $port . ")\n");
                } else {
                    // All daemons running
                    http_response_code (200);
                    print ("        All TCP ports seem to be open\n");
                }
            } else {
              print ("nmap not found in the system, nmap is required to test open ports\n");
            }
        }
     ?>


   </body>
</html>
