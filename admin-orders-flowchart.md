# Admin Orders Data Flow - https://geraisepatu.xyz/admin/orders

## Main Data Flow Flowchart

```mermaid
flowchart TD
    A[Admin Access /admin/orders] --> B[OrderController@index]
    B --> C{Apply Filters?}
    
    C -->|Yes| D[Filter by Status]
    C -->|Yes| E[Filter by Payment Status]
    C -->|Yes| F[Filter by Search Term]
    C -->|Yes| G[Filter by Date Range]
    
    D --> H[Query Builder]
    E --> H
    F --> H
    G --> H
    C -->|No| H
    
    H --> I[Apply Sorting]
    I --> J[Paginate Results (15 per page)]
    J --> K[Load Order Relations]
    K --> L[Return Orders View]
    
    L --> M[Display Orders Table]
    M --> N{Admin Action?}
    
    N -->|View Details| O[OrderController@show]
    N -->|Update Order Status| P[OrderController@updateStatus]
    N -->|Generate Invoice| R[OrderController@invoice]
    N -->|Export CSV| S[OrderController@export]
    
    O --> T[Load Order with Relations]
    T --> U[Display Order Details]
    
    P --> V[Validate Order Status Update]
    V --> W[Update Order Status in Database]
    W --> X[Send Email Notification]
    X --> Y[Redirect with Success]
    
    R --> FF[Load Order with Items & Products]
    FF --> GG[Generate Invoice View]
    
    S --> HH[Apply Export Filters]
    HH --> II[Generate CSV File]
    II --> JJ[Stream Download Response]
```

## Database Relations Flow

```mermaid
flowchart LR
    A[Order Model] --> B[User Model]
    A --> C[OrderItem Model]
    C --> D[Product Model]
    D --> E[ProductImage Model]
    
    A --> F[Order Attributes]
    F --> G[order_number]
    F --> H[status]
    F --> I[payment_status]
    F --> J[total_amount]
    F --> K[shipping_address JSON]
    F --> L[payment_details JSON]
```

## Order Status Flow

```mermaid
flowchart TD
    A[pending] --> B[processing]
    B --> C[shipped]
    C --> D[delivered]
    
    A --> E[cancelled]
    B --> E
```

## Payment Status Flow (Midtrans Automatic)

```mermaid
flowchart TD
    A[Customer Makes Payment] --> B[Midtrans Payment Gateway]
    B --> C{Payment Success?}
    C -->|Yes| D[Midtrans Webhook with Signature]
    C -->|No| E[Payment Failed Webhook]
    
    D --> D1[Verify Webhook Signature]
    D1 --> D2{Signature Valid?}
    D2 -->|Yes| F[Update payment_status = 'paid']
    D2 -->|No| D3[Log Security Warning & Reject]
    
    F --> F1[Store Midtrans Transaction Details]
    F1 --> G[Auto Update order_status = 'processing']
    G --> H[Send Payment Success Email]
    H --> H1[Lock Payment Status from Manual Changes]
    
    E --> E1[Verify Webhook Signature]
    E1 --> I[Update payment_status = 'failed']
    I --> I1[Store Midtrans Transaction Details]
    I1 --> J[Send Payment Failed Email]
    
    K[Midtrans Refund] --> K1[Verify Webhook Signature]
    K1 --> L[Update payment_status = 'refunded']
    L --> L1[Store Midtrans Transaction Details]
    L1 --> M[Send Refund Email]
```

## Filter & Search Flow

```mermaid
flowchart TD
    A[Request Parameters] --> B{Has Status Filter?}
    B -->|Yes| C[WHERE status = ?]
    B -->|No| D[Continue]
    
    C --> E{Has Payment Status Filter?}
    D --> E
    E -->|Yes| F[WHERE payment_status = ?]
    E -->|No| G[Continue]
    
    F --> H{Has Search Term?}
    G --> H
    H -->|Yes| I[WHERE order_number LIKE %search%]
    H -->|Yes| J[OR user.name LIKE %search%]
    H -->|Yes| K[OR user.email LIKE %search%]
    H -->|No| L[Continue]
    
    I --> M{Has Date Range?}
    J --> M
    K --> M
    L --> M
    M -->|Yes| N[WHERE created_at BETWEEN dates]
    M -->|No| O[Apply Sorting]
    
    N --> O
    O --> P[ORDER BY field direction]
    P --> Q[PAGINATE 15]
```

## Email Notification Flow

```mermaid
flowchart TD
    A[Order Status Update by Admin] --> B{Status = 'delivered'?}
    B -->|Yes| C[Send OrderDeliveredMail]
    B -->|No| D[Log Status Change]
    
    C --> E[Mail::to(customer_email)]
    E --> F[Queue Email Job]
    
    G[Midtrans Payment Success] --> H[Send PaymentSuccessMail]
    H --> I[Mail::to(customer_email)]
    I --> J[Queue Email Job]
    
    K[Midtrans Payment Failed] --> L[Send PaymentFailedMail]
    L --> M[Mail::to(customer_email)]
    M --> N[Queue Email Job]
```

## Export CSV Flow

```mermaid
flowchart TD
    A[Export Request] --> B[Apply Same Filters as Index]
    B --> C[Get All Matching Orders]
    C --> D[Load Relations: user, items.product]
    D --> E[Create CSV Headers]
    E --> F[Stream CSV Response]
    F --> G[Loop Through Orders]
    G --> H[Write Order Data to CSV]
    H --> I[Download File: orders-YYYY-MM-DD.csv]
```

## Key Data Points

### Order Statuses
- `pending` - Menunggu
- `processing` - Diproses  
- `shipped` - Dikirim
- `delivered` - Terkirim
- `cancelled` - Dibatalkan

### Payment Statuses
- `pending` - Menunggu
- `paid` - Dibayar
- `failed` - Gagal
- `refunded` - Dikembalikan

### Payment Methods
- QRIS, GoPay, OVO, DANA, ShopeePay
- Bank BCA, Mandiri, BRI, BNI
- Cash on Delivery (COD)

### Key Features
- Real-time filtering and search
- Bulk export to CSV
- Email notifications for status changes
- Invoice generation
- **Automatic payment validation via Midtrans**
- Order tracking and management

### Midtrans Integration
- **Automatic Payment Validation**: Payment status updated automatically via secure webhook
- **Signature Verification**: All webhooks verified with SHA512 signature for security
- **IP Whitelist Protection**: Webhook endpoint protected by Midtrans IP whitelist
- **Transaction Details Storage**: Complete Midtrans transaction data stored for audit
- **Manual Override Prevention**: Admin cannot manually change Midtrans-validated payment status
- **Real-time Email Notifications**: Automatic email sent for payment success/failure
- **Automatic Order Progression**: Order status automatically updated (pending → processing when paid)
- **Audit Trail**: All Midtrans updates logged with timestamps and transaction details