<?php
file_put_contents("log.txt", json_encode($_POST, JSON_PRETTY_PRINT), FILE_APPEND);

// Lê os dados JSON brutos da requisição
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Verifica se o pagamento foi aprovado
if (isset($data["data"]["id"])) {
    $payment_id = $data["data"]["id"];

    // Requisição para obter os detalhes do pagamento
    $ch = curl_init("https://api.mercadopago.com/v1/payments/" . $payment_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer APP_USR-2016932607138605-052508-42bd128ea282dc181e91d1571ac039fb-717395386"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $payment_info = json_decode($response, true);

    if ($payment_info["status"] == "approved") {
        // Aqui você recupera a descrição que contém o ID do usuário
        $descricao = $payment_info["description"];
        preg_match('/User (\d+)/', $descricao, $matches);
        $user_id = $matches[1];

        // Envia para o Discord pelo Webhook (ou via API do bot, como preferir)
        $mensagem = [
            "content" => "<@$user_id> pagamento confirmado! Aqui está sua conta:\nUsuário: exemplo\nSenha: 123456"
        ];

        $webhook_url = "https://discord.com/api/webhooks/1376360115539546232/L6Tt4Lnw5cPMTfohkBU14VX0Y3eraozHy7dKk5riha1Hhw_Nw904TUUP0Mnclt7_QoYW";
        $ch = curl_init($webhook_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($mensagem));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_exec($ch);
        curl_close($ch);
    }
}
?>