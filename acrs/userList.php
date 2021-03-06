<?php
set_include_path('./include');
require ("ui/validate.inc");
require_once('dbConfig.inc');
require_once ("dbCommand.inc");
require_once ("data/encodePOST.inc");
require_once ("data/encodeSQL.inc");
require_once ("data/encodeHTML.inc");
require_once ("ui/siteLayout.inc");
require_once ("useful.inc");

$corrMsg = '';
$fail = dbConnect($db_conn);
if ($fail != '')
{
   notifyError($fail, "userList.php");
   $corrMsg = "<li>Internal: failed access to contest database</li>";
} else
{
   if (!isAdministrator())
   {
      $corrMsg = '<li>Restricted to ACRS Administrator.</li>';
   }
}
$queryParms = $_POST;
$userList = false;
if (isset ($queryParms['submit']))
{
  $query = 'select userID, email, givenName, familyName, accountName from registrant where ' . strSQLTest('email', $queryParms['email'], 256) .
    ' or ' . strSQLTest('givenName', $queryParms['givenName'], 72) .
    ' or ' . strSQLTest('familyName', $queryParms['familyName'], 72);
  $userList = dbQuery($db_conn, $query);
  if ($userList === false)
  {
    $corrMsg .= '<li>' . notifyError(dbErrorText(), 'admin/userList.php')
    . '</li>';
  }
}
else
{
  $corrMsg .= '<li>Missing post data</li>';
}

startHead("Matching Users");
startContent();
if ($corrMsg != '')
{
   echo '<ul class="error">' . $corrMsg . '</ul>';
}
else
{
  while ($user = dbFetchAssoc($userList))
  {
    echo '<div class="entry">' . strhtml($user['givenName']) . ' ' .
    strhtml($user['familyName']). ' (' . strhtml($user['accountName']) .
    ') ';
    echo '<a href="resetByAdmin.php?id=' . strhtml($user['userID']) . '">reset password</a>'."\n";
    echo '<div class="email">' . strhtml($user['email']).'</div>';
    echo '</div>';
  }
}
echo '<p><a href="accountHelp.php">Try another query</a></p>';
echo '<p><a href="index.php">Return to registration</a></p>';
endContent();

if ($db_conn)
{
   dbClose($db_conn);
}


/*
 Copyright 2011 International Aerobatic Club, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */
?>
