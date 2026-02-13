<?php
    // função para gerar login
    function gerarLogin($nomeCompleto) {

        // Remover espaços extras no começo e fim
        $nomeCompleto = trim($nomeCompleto);

        // Validar: apenas letras e espaços
        if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nomeCompleto)) {
            return "Nome inválido. Use apenas letras.";
        }

        // Transformar tudo em minúsculo
        $nomeCompleto = mb_strtolower($nomeCompleto, 'UTF-8');

        // Separar o nome em partes usando espaço como referência
        $partes = explode(" ", $nomeCompleto);

        // Remover possíveis espaços duplicados
        $partes = array_filter($partes);

        //  Reorganizar os índices do array
        $partes = array_values($partes);

        // Pegar o primeiro nome
        $primeiroNome = $partes[0];

        // Pegar o último nome
        $ultimoNome = $partes[count($partes) - 1];

        // Pegar a primeira letra do último nome
        $primeiraLetraUltimo = $ultimoNome[0];

        // Gerar número aleatório entre 0 e 99
        $numeroAleatorio = rand(0, 99);

        // Montar o login
        $login = $primeiroNome . $primeiraLetraUltimo . $numeroAleatorio;

        // Transformar tudo em maiúsculo
        $login = strtoupper($login);

        return $login;
    }


    // Exemplo de uso
    $nomeDigitado = "christian  samuel";

    echo gerarLogin($nomeDigitado);

?>
