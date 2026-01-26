# Nepal Can Move PHP SDK

[![Lint & Test PR](https://github.com/achyutkneupane/nepal-can-move-php/actions/workflows/prlint.yml/badge.svg)](https://github.com/achyutkneupane/nepal-can-move-php/actions/workflows/prlint.yml)

A strictly typed, and expressive PHP SDK for integrating with the **Nepal Can Move (NCM)** logistics API.

This package provides a fluent wrapper around the NCM API, normalizing API responses into clean Data Transfer Objects (DTOs), leveraging PHP 8.1+ Enums for type safety, and handling all authentication and error states automatically.

## Requirements

- PHP: **8.2+**
- Laravel: **10.x**, **11.x**, or **12.x**

## Installation

Install the package via Composer:

```bash
composer require achyutn/nepal-can-move
```

Publish the configuration file:

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
> API Limits
>
> Please be mindful of the NCM API limits to avoid IP throttling:
>
> Order Creation: **1,000 per day**.  
> Order Views (Detail/Status): **20,000 per day**.

### Fetching Branches

Retrieve all available NCM branches as strictly typed `Branch` objects.

```php
use AchyutN\NCM\Facades\NCM;

$branches = NCM::getBranches();

$tinkune = $branches->firstWhere('name', 'TINKUNE');

echo $tinkune->phone;
```

### Calculating Shipping Rates

Calculate delivery charges using the `DeliveryType` enum.

```php
use AchyutN\NCM\Enums\DeliveryType;

$charge = NCM::getDeliveryCharge(
    source: $tinkune,
    destination: $pokhara,
    deliveryType: DeliveryType::BranchToDoor
);
```

### Creating an Order

Use the `CreateOrderRequest` DTO to ensure all required fields are present.

```php
use AchyutN\NCM\Data\CreateOrderRequest;
use AchyutN\NCM\Enums\DeliveryType;

$request = new CreateOrderRequest(
    name: 'Achyut Neupane',
    phone: '9800000000',
    codCharge: '1500',
    address: 'Lakeside, Pokhara',
    fbranch: 'KATHMANDU',
    branch: 'POKHARA',
    package: 'Books',
    deliveryType: DeliveryType::DoorToDoor
);

$order = NCM::createOrder($request);

echo $order->id;
```

> Note: The NCM API uses different naming conventions across its endpoints (e.g., Pickup/Collect vs Door2Door).
> This SDK normalizes these into the DeliveryType enum. You should always use the Enum cases; the SDK handles the underlying API string transformations automatically.

### Order Management

The `Order` object exposes rich behavior.

#### Fetching an Order

```php
$order = NCM::getOrder(12345);
```

#### Status History

```php
$history = $order->status();

foreach ($history as $status) {
    echo $status->status . ' - ' . $status->addedTime->diffForHumans();
}
```

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
use AchyutN\NCM\Data\RedirectOrderRequest;

$redirect = new RedirectOrderRequest(
    orderid: $order->id,
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
use AchyutN\NCM\Enums\TicketType;

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

## Exception Handling

```php
use AchyutN\NCM\Exceptions\NCMException;

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
