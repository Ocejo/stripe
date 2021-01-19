<?php
use \Stripe\Stripe;
try {
  # vendor using composer
  require_once('vendor/autoload.php');

  \Stripe\Stripe::setApiKey('sk_test_51G9HjnKAZZhSOcNl6LxbGVUJd7mZaj1sVuKN3EhWIwW5TS3AvhPy2DnH21vzcydWQWH7z9GOPF1Bt9mURv5BDVSr00Cei1GQRo');

  header('Content-Type: application/json');

  # retrieve json from POST body
  $json_str = file_get_contents('php://input');
  $json_obj = json_decode($json_str);

  $intent = \Stripe\PaymentIntent::create([
      'payment_method' => $json_obj->payment_method_id,
      'amount' => 3099,
      'currency' => 'mxn',
      'payment_method_options' => [
          'card' => [
              'installments' => [
                  'enabled' => true
              ]
          ]
      ],
  ]);

  echo json_encode([
      'intent_id' => $intent->id,
      'available_plans' => $intent->payment_method_options->card->installments->available_plans
  ]);
}
catch (\Stripe\Exception\CardException $e){
  # "e" contains a message explaining why the request failed
  echo 'Card Error Message is:' . $e->getError()->message . '
';
  echo json_encode([
      'error_message' => $e->getError()->message
  ]);
}
catch (\Stripe\Exception\InvalidRequestException $e) {
  // Invalid parameters were supplied to Stripe's API
  echo 'Invalid Parameters Message is:' . $e->getError()->message . '
';
  echo json_encode([
      'error_message' => $e->getError()->message
  ]);
}
?>