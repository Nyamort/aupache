<?php

namespace App\Services;

class CommandService
{
    public function run(string $command): void
    {
        $descriptorspec = array(
            0 => array("pipe", "r"),  // // stdin est un pipe où le processus va lire
            1 => array("pipe", "w"),  // stdout est un pipe où le processus va écrire
            2 => array("file", "storage/logs/command.log", "a") // stderr est un fichier
        );
        $process = proc_open("echo 'www-data' | /usr/bin/sudo -S $command", $descriptorspec, $pipes);

        if (is_resource($process)) {
            // $pipes ressemble à :
            // 0 => fichier accessible en écriture, connecté à l'entrée standard du processus fils
            // 1 => fichier accessible en lecture, connecté à la sortie standard du processus fils
            // Toute erreur sera ajoutée au fichier /tmp/error-output.txt

            fwrite($pipes[0], '<?php print_r($_ENV); ?>');
            fclose($pipes[0]);

            echo stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            // Il est important que vous fermiez les pipes avant d'appeler
            // proc_close afin d'éviter un verrouillage.
            $return_value = proc_close($process);

            echo "La commande a retourné $return_value\n";
        }
    }
}