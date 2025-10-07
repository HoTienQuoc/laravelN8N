<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class PaddleController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            $eventType = $request->input('event_type');
            $data = $request->input('data');

            Log::info('Paddle webhook received', [
                'event_type' => $eventType,
                'subscription_id' => $data['id'] ?? null
            ]);

            switch ($eventType) {
                case 'subscription.activated':
                    $this->handleSubscriptionActivated($data);
                    break;

                case 'subscription.updated':
                    $this->handleSubscriptionUpdated($data);
                    break;

                case 'subscription.canceled':
                    $this->handleSubscriptionCanceled($data);
                    break;

                case 'subscription.past_due':
                    $this->handleSubscriptionPastDue($data);
                    break;

                default:
                    Log::info('Unhandled webhook event type', ['event_type' => $eventType]);
                    break;
            }

            return response()->json(['status' => 'success'], Response::HTTP_OK);

        } catch (\Exception $e) {
            Log::error('Paddle webhook error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json(['status' => 'error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function handleSubscriptionActivated(array $data)
    {
        $email = $data['custom_data']['email'] ?? null;

        if (!$email) {
            Log::warning('No email found in subscription activation webhook', ['data' => $data]);
            return;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::warning('User not found for subscription activation', ['email' => $email]);
            return;
        }

        // Extract subscription type from price name or product name
        $subscriptionType = $this->extractSubscriptionType($data);

        $user->update([
            'status' => 'active',
            'subscription_type' => $subscriptionType,
            'current_billing_period_start' => Carbon::parse($data['current_billing_period']['starts_at']),
            'current_billing_period_end' => Carbon::parse($data['current_billing_period']['ends_at']),
            'subscription_id' => $data['id']
        ]);

        Log::info('User subscription activated', [
            'user_id' => $user->id,
            'subscription_id' => $data['id'],
            'subscription_type' => $subscriptionType
        ]);
    }

    private function handleSubscriptionUpdated(array $data)
    {

    }
    private function handleSubscriptionCanceled(array $data)
    {

    }
    private function handleSubscriptionPastDue(array $data)
    {

    }
    private function extractSubscriptionType(array $data)
    {
        // Check if there are items in the subscription
        if (isset($data['items']) && count($data['items']) > 0) {
            $firstItem = $data['items'][0];

            if (isset($firstItem['product']['name'])) {
                $productName = strtolower($firstItem['product']['name']);
                if (str_contains($productName, 'premium')) {
                    return 'premium';
                } elseif (str_contains($productName, 'basic')) {
                    return 'basic';
                }
            }
        }

        return null;
    }

}
