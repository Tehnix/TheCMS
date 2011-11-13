<?php
# Redirect to cgi-bin
Header( "HTTP/1.1 301 Moved Permanently" ); 
Header( "Location: /TheCMS-dev/_dev/cgi-bin/index.py" );
# If that fails, try meta refresh, and a fallback link
print '<html>
<head>
    <meta http-equiv="refresh" content="0; url=/_labs/_dev/cgi-bin/index.py">
</head>
<body>
    <a href="cgi-bin/index.py">Go to index</a>
</body>
</html>';
?>