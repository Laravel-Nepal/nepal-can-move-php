# Nepal Can Move PHP SDK

[![Lint & Test PR](https://github.com/Laravel-Nepal/nepal-can-move-php/actions/workflows/prlint.yml/badge.svg)](https://github.com/achyutkneupane/nepal-can-move-php/actions/workflows/prlint.yml)

A strictly typed, and expressive PHP SDK for integrating with the **Nepal Can Move (NCM)** logistics API.

This package provides a fluent wrapper around the NCM API, normalizing API responses into clean Data Transfer Objects (DTOs), leveraging PHP 8.1+ Enums for type safety, and handling all authentication and error states automatically.

## Requirements

- PHP: **8.2+**
- Laravel: **10.x**, **11.x**, or **12.x**

## Installation

Install the package via Composer:

```bash
composer require laravel-nepal/nepal-can-move
```

*(Optional)* Publish the configuration file:

```bash
php artisan vendor:publish --tag="nepal-can-move"
```

## Configuration

Add your NCM credentials to your `.env` file. Enable sandbox mode for testing.

```env
NCM_TOKEN=your_api_token_here
NCM_SANDBOX_MODE=true
```

> You can get your token by registering a vendor account then from the portal dashboard.

## Usage

> [!WARNING]  
> ### API Limits
>
> Please be mindful of the NCM API limits to avoid IP throttling:
>
> Order Creation: **1,000 per day**.  
> Order Views (Detail/Status): **20,000 per day**.

### Fetching Branches

Retrieve all available NCM branches as strictly typed `Branch` objects.

```php
use LaravelNepal\NCM\Facades\NCM;

$branches = NCM::getBranches();

$tinkune = $branches->firstWhere('name', 'TINKUNE');

echo $tinkune->phone;
```

### Calculating Shipping Rates

Calculate delivery charges using the `DeliveryType` enum.

```php
use LaravelNepal\NCM\Enums\DeliveryType;

$charge = NCM::getDeliveryCharge(
    source: $tinkune,
    destination: $pokhara,
    deliveryType: DeliveryType::BranchToDoor
);
```

### Creating an Order

Use the `CreateOrderRequest` DTO to ensure all required fields are present.

```php
use LaravelNepal\NCM\Data\CreateOrderRequest;
use LaravelNepal\NCM\Enums\DeliveryType;

$request = new CreateOrderRequest(
    name: 'Achyut Neupane',
    phone: '9800000000',
    codCharge: '1500',
    address: 'Lakeside, Pokhara',
    sourceBranch: 'KATHMANDU',
    destinationBranch: 'POKHARA',
    package: 'Books',
    deliveryType: DeliveryType::DoorToDoor
);

$order = NCM::createOrder($request);

echo $order->id;
```

> [!IMPORTANT]  
> The NCM API uses different naming conventions across its endpoints (e.g., Pickup/Collect vs Door2Door).  
> This SDK normalizes these into the DeliveryType enum. You should always use the Enum cases; the SDK handles the underlying API string transformations automatically.

### Order Management

The `Order` object exposes rich behavior.

#### Fetching an Order

```php
$order = NCM::getOrder(12345);
```

#### Status History

```php
$history = $order->statusHistory();

foreach ($history as $status) {
    echo $status->status . ' - ' . $status->addedTime->diffForHumans();
}
```

##### Latest Order Status

```php
echo $order->status();
````

#### Comments

```php
$order->addComment('Customer requested evening delivery.');

$comments = $order->comments();
```

#### Return & Exchange

```php
$order->return('Customer refused delivery');

$order->exchange();
```

### Redirecting an Order

```php
use LaravelNepal\NCM\Data\RedirectOrderRequest;

$redirect = new RedirectOrderRequest(
    orderId: $order->id,
    name: 'Not Achyut Neupane',
    phone: '9811111111',
    address: 'New Address, Kathmandu',
    orderIdentifier: 'ORD-REF-002',
    destinationBranchId: 5,
    codCharge: 1600.00
);

NCM::redirectOrder($redirect);
```

### Support Tickets

#### General Support

```php
use LaravelNepal\NCM\Enums\TicketType;

NCM::createSupportTicket(
    TicketType::OrderProcessing,
    "My order is stuck in Pickup status for 3 days."
);
```

#### COD Transfer Request

```php
$ticketId = NCM::createCODTransferTicket(
    bankName: 'Nabil Bank',
    accountHolderName: 'My Company Pvt Ltd',
    accountNumber: '001001001001'
);
```

## Webhooks

The Nepal Can Move SDK allows you to manage your webhook configuration and transform incoming `POST` data into strictly typed objects.

### Configuring Webhooks

You can programmatically set, test, or remove your webhook URL:

```php
// Set the URL where NCM will push updates
$ncm->setWebhookUrl('https://your-app.com/api/ncm/webhook');

// Send a test payload to your URL to verify connectivity
$ncm->testWebhookUrl('https://your-app.com/api/ncm/webhook');

// Disable webhooks
$ncm->removeWebhookUrl();
```

### Handling Webhook Payloads

When NCM sends a status update to your server, use `parseWebhook` to convert the raw request data into a [StatusEvent](src/Data/StatusEvent.php) DTO. This automatically maps technical events to your `OrderStatus` enums using the [`toOrderStatus`](src/Enums/EventStatus.php#L37) method.

```php
use LaravelNepal\NCM\Exceptions\NCMException;
use LaravelNepal\NCM\Enums\OrderStatus;

try {
    $event = $ncm->parseWebhook($payload);

    echo $event->orderIds; // e.g., [123, 124]
    echo $event->event->getLabel(); // e.g., "Delivered"
    
    // Get the normalized OrderStatus enum
    $status = $event->getOrderStatus(); 
    
    if ($status === OrderStatus::Delivered) {
        // Perform business logic
    }
} catch (NCMException $NCMException) {
    // Handle unknown event types or malformed data
}
```

> [!TIP]
> Since NCM webhooks do not currently include a cryptographic signature, it is recommended to add a unique query parameter to your webhook URL (e.g., `?secret=your-random-key`) and verify it in your controller to ensure the request is legitimate.

## Exception Handling

```php
use LaravelNepal\NCM\Exceptions\NCMException;

try {
    NCM::createOrder($request);
} catch (NCMException $e) {
    return back()->withErrors($e->getMessage());
}
```

## Contributing

Contributions are welcome! Please create a pull request or open an issue if you find any bugs or have feature requests.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Support

If you find this package useful, please consider starring the repository on GitHub to show your support.
