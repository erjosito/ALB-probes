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
     ?>
     <?php
        # Reachability test
        if (reachabilityTest) {
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
              print ("At least one target host does not seem to be all reachable (" . $host . ")\n");
            } else {
              // Ping did work
              http_response_code (200);
              print ("All target hosts seem to be reachable\n");
            }
        }
     ?>
   </body>
</html>
