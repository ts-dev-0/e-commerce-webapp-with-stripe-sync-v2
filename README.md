# E-Commerce Webapp with Stripe Sync v2

Modern E-Commerce application using Laravel, React and InertiaJS

## 🚀 Key Features

### Authentication・User Management

- **User Authentication**: Secure signup and login feature using Laravel Fortify
- **2 Factor Auth**: 2FA support for enhanced security
- **Mail Authentication**: User mail verification feature
- **Passwor Reset**: Account recovery feature via email

### Manage Product

- **Manage product infromation**: Manage name, description, price, stock
- **Category System**: Categorize products into multiple categories (Manu-to-Many)
- **Search products**: 

### Shopping Cart

- **User-specific car**: Individual cart management for earch user

### Manage Order

- **Create order**: Automatically generate orders from cart contents
- **Manage order status**
  - 🟡 Pending
  - ✅ Paid
  - ✅ Completed
  - ❌ Canceled
- **Cancel feature**: Only Order in Pending status can be canceled
- ***Order detail saved**: Persis order information for each product in OrderItem 

## 🛠️ Tech Stack

### Back-end
- **Laravel 12**
- **PHP 8.2+**
- **Fortify**
- **Wayfinder**

### Front-end
- **React 19**
- **TypeScript**
- **Inertia.js**
- **Tailwind CSS 4**
- **Shadcn/ui**

### Build Tools
- **Vite**
- **TypeScript Compiler**
- **ESLint**
- **Prettier**

### Database
- **MySQL**

### Test・Development
- **PHPUnit**
- **Faker**
- **Laravel Pint**

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+
- npm / yarn / bun

## 📄 License

MIT License