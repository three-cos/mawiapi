# API Salesmax

- [Подготовка](#Подготовка)
- [Установка](#Установка)
- [Инициализация](#Инициализация)
- [Клиенты](#Клиенты)
- [Сделки](#Сделки)
- [Продукты](#Продукты)
- [Контактные лица](#Контактные-лица)
- [Платежи](#Платежи)
- [Счета](#Счета)
- [События в календаре](#События-в-календаре)
- [Документы](#Документы)
- [Лицензия](#Лицензия)

## Подготовка
Актуальный документ с описанием параметров вызова API можно получить отправив запрос в техподдержку Mawisoft [info@mawisoft.com](info@mawisoft.com). Эта бибилиотека была создана опираясь на [данный документ](/Docs/API%20CRM%20SalesMax%206.8.docx).

## Установка
```bash
composer require wardenyarn/mawiapi
```

### Инициализация 
```php
use Wardenyarn\MawiApi\MawiSoftApi;
use Wardenyarn\MawiApi\Exceptions\MawiApiException;

$host = 'your_company'; // из http://your_company.mawisoft.ru
$login = 'admin@your_company.ru';
$password = 'secret';

try {
    $api = new MawiSoftApi($host, $login, $password);
} catch (MawiApiException $e) {
    echo $e->getMessage();
}
```

**Лимит на возвращаемые записи** 
```php
// Default: 20
$api->setLimit(100);
```

**Кол-во записей на один запрос**
```php
// Default: 20
$api->setPageSize(50);
```

#### Клиенты
```php
// Создание
$client_id = $api->setClient([
    'name' => 'new client',
    ...
]);

// Редактирование
$client_id = $api->editClient($client_id, [
    'name' => 'client edited',
    ...
]);

// Получение записи
$client = $api->getClient($client_id);
echo $client->name;  

// Получение $api->limit записей с возможностью фильтрации
$filter = ['name' => 'ОAО'];
foreach ($api->getClients($filter) as $client) {
    echo $client->name; // ОАО Агропром, ОАО Рыбторг, ОАО Главпочтамп
}

// Назначение категории
$api->setClientCategory($client_id, $category_id);

// Исключение из категории
$api->removeClientCategory($client_id, $category_id);

// Добавить платежный реквизит
$api->setClientCustomer($client_id, $customer);

// Получение всех платежных реквизитов
$customers = $api->getClientCustomers($client_id);
```

#### Сделки

```php
use Wardenyarn\MawiApi\Entities\Proposal;

// Создание
$proposal_id = $api->setProposal($client_id, $user_id, $product_id, Proposal::VAT_18, [
    'price' => 9000,
    ...
]);

// Редактирование
$proposal_id = $api->editProposal($proposal_id, $user_id, $product_id, Proposal::VAT_20, [
    'price' => 10000,
    ...
]);

// Получение
$proposal = $api->getProposal($proposal_id);
echo $proposal->price;

// Получение $api->limit записей с возможностью фильтрации
$filter = ['clientName' => 'MegaClient'];
foreach ($api->getProposals($filter) as $proposal) {
    echo $proposal->price;
}

// Товары в сделках
foreach ($api->getProposalItems($filter) as $proposalItem) {
    echo $proposalItem->description;
}
```


#### Продукты

```php
use Wardenyarn\MawiApi\Entities\Product;

// Создание
$product_id = $api->setProduct(Product::TYPE_SIMPLE, [
    'name' => 'new product',
    ...
]);

// Редактирование
$product_id = $api->editProduct($product_id, [
    'name' => 'edited product',
    ...
]);

// Получение
$product = $api->getProduct($product_id);
echo $product->name;

// Получение $api->limit записей с возможностью фильтрации
$filter = ['name' => 'BFG-9000'];
foreach ($api->getProducts($filter) as $product) {
    echo $product->name;
}

// Получение $api->limit отгруженных продуктов с возможностью фильтрации
foreach ($api->getShippedProducts($filter) as $shipped_product) {
    echo $shipped_product->name;
}

// Получение $api->limit не отгруженных продуктов с возможностью фильтрации
// Внимание! Получает все записи одним запросом
foreach ($api->getUnshippedProducts($filter) as $unshipped_product) {
    echo $unshipped_product->name;
}

// Получение $api->limit отгрузок с возможностью фильтрации
foreach ($api->getShippings($filter) as $shipping) {
    echo $shipping->date;
}

// Получение отгрузки
$shipping = $api->getShipping($id);

// Список категорий продуктов
$shipping = $api->getProductCategories();
```

#### Контактные лица

```php
// Получение $api->limit записей с возможностью фильтрации
$filter = ['name' => 'Name'];
foreach ($api->getPeople($filter) as $person) {
    echo $person->name; 
}

// Создание
$person_id = $api->setPerson($clientId, [
    'name' => 'John Doe',
    ...
]);

// Редактирование
$person_id = $api->editPerson($person_id, [
    'name' => 'Jane Doe',
    ...
]);

// Получение Юр. Лица
$seller = $api->getSeller($seller_id);

// Получение списка сотрудников
$department_id = -1; // Все сотрудники
foreach ($api->getUsers($department_id) as $user) {
    echo $user->name;
}

```

#### Платежи

```php
// Получение $api->limit записей с возможностью фильтрации
$filter = ['clientName' => 'Umbrella Corp.'];
foreach ($api->getPayments($filter) as $payment) {
    echo $payment->sum; 
}

// Получение
$payment = $api->getPayment($payment_id);

// Напоминание о задолженности
$paymentPrint = $api->getPaymentPrint($payment_id);

```

#### Счета

```php
// Получение $api->limit записей с возможностью фильтрации
$filter = ['clientName' => 'Tyrell Corporation'];
foreach ($api->getInvoices($filter) as $invoice) {
    echo $invoice->sum; 
}

// Создание
$invoice_id = $api->setInvoice($client_id, $userId, $payerId, $customerId, [
    'price' => 1000,
    'measureUnit' => 'шт.',
    ...
]);

// Получение
$invoice = $api->getInvoice($invoice_id);
```

#### События в календаре

```php
use Wardenyarn\MawiApi\Entities\Event;

// Создание
$event_id = $api->setEvent(Event::TYPE_EMAIL, $clientId, $userId, $params);

// Отчет по событию
$report_id = $api->setReport($event_id, $message, $success = 'on');

// Получение $api->limit записей с возможностью фильтрации
$filter = ['date' => date('Ymd')];
foreach ($api->getEvents($filter) as $event) {
    echo $event->title; 
    echo $event->event->description; 
}
```

#### Документы

```php
// Получение $api->limit записей с возможностью фильтрации
$filter = ['from' => date('d.m.Y'), 'to' => date('d.m.Y')];
foreach ($api->getDocuments($filter) as $document) {
    echo $document->description; 
}

// Получение
$document = $api->getDocument($id);

// Скачивание документа
$api->downloadDocument($document_id, $storage_dir);
```

## Лицензия

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.