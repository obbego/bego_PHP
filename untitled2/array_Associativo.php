<?php

function mostraNazioneDaCitta($elenco, $citta) {
    if (array_key_exists($citta, $elenco)) {
        $nazione = $elenco[$citta];
        echo "La città $citta si trova in $nazione.<br><br>";
    } else {
        echo "La città $citta non è presente nell'elenco.<br><br>";
    }
}

function mostraCittaDaNazione($elenco, $nazione) {
    foreach ($elenco as $citta => $paese) {
        if ($paese === $nazione) {
            echo "La nazione $nazione comprende la città $citta.<br><br>";
            return;
        }
    }
    echo "Nessuna città trovata per la nazione $nazione.<br><br>";
}

function stampaElencoCompleto($elenco) {
    if (empty($elenco)) {
        echo "L'elenco è vuoto.<br>";
        return;
    }

    echo "<strong>Elenco completo città - nazioni:</strong><br>";
    echo "---------------------------------------<br>";

    foreach ($elenco as $citta => $paese) {
        echo "$paese - $citta<br>";
    }

    echo "<br>";
}

$cittaNazioni = [
    "Madrid" => "Spagna",
    "Vienna" => "Austria",
    "Oslo" => "Norvegia",
    "Dublino" => "Irlanda",
    "Praga" => "Repubblica Ceca",
    "Helsinki" => "Finlandia"
];

mostraNazioneDaCitta($cittaNazioni, "Madrid");
mostraCittaDaNazione($cittaNazioni, "Irlanda");
stampaElencoCompleto($cittaNazioni);

?>