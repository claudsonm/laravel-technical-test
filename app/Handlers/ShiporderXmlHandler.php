<?php

namespace App\Handlers;

use App\Order;
use Illuminate\Support\Arr;

class ShiporderXmlHandler extends Handler
{
    protected int $successCount = 0;

    protected int $errorCount = 0;

    public function handle(): Handler
    {
        foreach ($this->content['shiporder'] as $pendingOrder) {
            try {
                $order = Order::create([
                    'id' => $pendingOrder['orderid'],
                    'destination' => $pendingOrder['shipto']['name'],
                    'address' => $pendingOrder['shipto']['address'],
                    'city' => $pendingOrder['shipto']['city'],
                    'country' => $pendingOrder['shipto']['country'],
                    'person_id' => $pendingOrder['orderperson'],
                ]);
                foreach ($this->getItemsFrom($pendingOrder) as $item) {
                    $order->items()->create([
                        'name' => $item['title'],
                        'notes' => $item['note'],
                        'quantity' => $item['quantity'],
                        'price' => (float) $item['price'],
                    ]);
                }
                $this->successCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
            }
        }

        return $this;
    }

    public function getOutput(): array
    {
        $message = 'File processed: '.$this->successCount.' new orders imported and '.$this->errorCount.' orders with errors.';
        $level = $this->errorCount > 0 ? 'warning' : 'success';

        return [$message, $level];
    }

    /**
     * @param array|string $order
     *
     * @return array
     */
    protected function getItemsFrom($order)
    {
        if (Arr::exists($items = $order['items']['item'], 'quantity')) {
            return [$items];
        }

        return $items;
    }
}
