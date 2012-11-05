<?php

require_once('database.php');

$database = new Database();

fwrite(STDOUT, "\nHey! Program will terminate on command 'END'\n\n");

$data = array();

do {
  $command = trim(fgets(STDIN));
  $data[] = explode(' ', $command);
} while ($command != 'END');

fwrite(STDOUT, "-- OUTPUT --\n");

try {
  foreach ($data as $cmd) {
    switch ($cmd[0]) {
      case 'BEGIN':
        $database->begin();
        break;
      case 'ROLLBACK':
        $database->rollback();
        break;
      case 'COMMIT':
        $database->commit();
        break;
      case 'GET':
        echo $database->get($cmd[1])."\n";
        break;
      case 'SET':
        $database->set($cmd[1],@$cmd[2]);
        break;
      case 'UNSET':
        $database->delete($cmd[1]);
        break;
      case 'NUMEQUALTO':
        echo $database->numEqualTo($cmd[1])."\n";
        break;
      case 'END':
        exit(0);
        break;
      default:
        fwrite(STDOUT, "There is no command $cmd[0]!\n");
        exit(0);
        break;
    }
  }
} catch(Exception $e) {
  fwrite(STDOUT, $e->getMessage());
}