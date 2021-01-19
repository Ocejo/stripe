<?php

# vendor using composer
require_once('vendor/autoload.php');

\Stripe\Stripe::setApiKey('sk_test_51G9HjnKAZZhSOcNl6LxbGVUJd7mZaj1sVuKN3EhWIwW5TS3AvhPy2DnH21vzcydWQWH7z9GOPF1Bt9mURv5BDVSr00Cei1GQRo');

header('Content-Type: application/json');

# retrieve json from POST body
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

if (isset($json_obj['selected_plan'])) {
    $confirm_data = ['payment_method_options' =>
        [
            'card' => [
                'installments' => [
                    'plan' => $json_obj['selected_plan']
                ]
            ]
        ]
    ];
}

$intent = \Stripe\PaymentIntent::retrieve(
    $json_obj['payment_intent_id']
);

$intent->confirm($params = $confirm_data);

echo json_encode([
    'status' => $intent->status,
]);

?>