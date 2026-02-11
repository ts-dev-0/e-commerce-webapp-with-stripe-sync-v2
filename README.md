# E-Commerce Webapp with Stripe Sync v2

Laravel、React、InertiaJSを使用した現代的なEコマースアプリケーション。Stripe統合の準備が整った柔軟なショッピングカート、注文管理システムを備えています。

## 🚀 主な機能

### 認証・ユーザー管理
- **ユーザー認証**: Laravel Fortify を使用し、安全なサインアップ・ログイン機能
- **2要素認証**: セキュリティ強化のための2FA対応
- **メール認証**: ユーザーメール確認機能
- **パスワードリセット**: 安全なパスワード復旧機能

### 商品管理
- **商品情報管理**: 名前、説明、価格、在庫管理
- **カテゴリシステム**: 商品を複数カテゴリに分類可能（many-to-many関係）
- **新着商品表示**: `newArrivals()`スコープで最新商品を取得

### ショッピングカート
- **ユーザーごとのカート**: 各ユーザーの個別カート管理
- **柔軟な商品操作**:
  - `addProduct()`: 商品をカートに追加（重複時は数量増加）
  - `updateProductQuantity()`: 数量の更新
  - `removeProduct()`: 商品の削除
- **数量バリデーション**: 最小数量1を強制

### 注文管理
- **注文作成**: カートの内容から自動的に注文を生成
- **注文ステータス管理**:
  - 🟡 Pending（保留中）
  - ✅ Paid（支払済み）
  - ✅ Completed（完了）
  - ❌ Canceled（キャンセル）
- **キャンセル機能**: Pending状態の注文のみキャンセル可能
- **注文詳細保存**: OrderItemで各商品の注文時の情報を永続化

### フロントエンド
- **React + TypeScript**: 最新のモダンなUI開発
- **Inertia.js**: シームレスなLaravel-React統合
- **Tailwind CSS**: ユーティリティベースのスタイリング
- **Radix UI**: アクセシビリティを重視したコンポーネントライブラリ
  - Dialog、Dropdown Menu、Select、Checkbox、Avatar等
- **Hot Module Replacement**: 開発時のホットリロード対応

## 🏗️ プロジェクト構成

```
app/
├── Models/
│   ├── User.php              # ユーザー（2FA対応）
│   ├── Product.php           # 商品
│   ├── Category.php          # カテゴリ
│   ├── Cart.php              # ショッピングカート
│   ├── Order.php             # 注文
│   └── OrderItem.php         # 注文アイテム
├── Http/
│   ├── Controllers/
│   │   └── ProductController.php
│   └── Requests/
├── Enums/
│   └── OrderStatus.php       # 注文ステータス（型安全なEnum）
└── Actions/
    └── Fortify/              # 認証関連のアクション

resources/
├── js/
│   ├── pages/
│   │   ├── welcome.tsx       # ホームページ
│   │   ├── dashboard.tsx     # ダッシュボード
│   │   ├── product/
│   │   │   └── show.tsx      # 商品詳細
│   │   ├── auth/             # 認証ページ
│   │   └── settings/         # 設定ページ
│   ├── components/           # 再利用可能なコンポーネント
│   ├── layouts/              # ページレイアウト
│   └── hooks/                # カスタムReactフック
└── css/
    └── app.css               # グローバルスタイル

database/
├── migrations/               # テーブル定義
├── factories/                # テストデータ生成
└── seeders/                  # 初期データ投入

routes/
├── web.php                   # Webルート
└── settings.php              # 設定ルート
```

## 💾 データベーススキーマ

### 主要テーブル関係図

```
Users (1) ─── (1) Carts (many) ─── (through cart_items) ─── (many) Products
   │                                                              │
   │                                                              │
   (1)                                                      (many-to-many)
   │                                                              │
   (many)                                                  Categories
   │
Orders ─── (many) OrderItems ─── (many) Products

```

## 🛠️ 技術スタック

### バックエンド
- **Laravel 12**: PHPフレームワーク
- **PHP 8.2+**: サーバーサイド言語
- **Fortify**: ビルトイン認証システム
- **Wayfinder**: ルーティング補助

### フロントエンド
- **React 19**: UIライブラリ
- **TypeScript**: 静的型付け
- **Inertia.js**: モノリシックなSPA
- **Tailwind CSS 4**: ユーティリティCSS
- **Radix UI**: ヘッドレスUI

### ビルドツール
- **Vite**: 高速なビルドツール
- **TypeScript Compiler**: 型チェック
- **ESLint**: コード品質管理
- **Prettier**: コードフォーマッタ

### データベース
- **PostgreSQL / MySQL**: リレーショナルDB対応

### テスト・開発
- **PHPUnit**: PHPユニットテスト
- **Faker**: ダミーデータ生成
- **Laravel Pint**: PHPコード品質管理

## 🚀 セットアップ・実行

### 前提条件
- PHP 8.2+
- Composer
- Node.js 18+
- npm / yarn / bun

### インストール手順

```bash
# リポジトリをクローン
git clone <repository-url>
cd e-commerce-webapp-with-stripe-sync-v2

# 依存パッケージをインストール
composer setup
# または個別に実行:
# composer install
# cp .env.example .env
# php artisan key:generate
# php artisan migrate
# npm install
# npm run build
```

### 開発サーバーの起動

```bash
# パッケージ管理ツールが組み込みサーバーをサポートしている場合:
php artisan serve

# 別ターミナルでフロントエンドの開発サーバーを起動:
npm run dev
```

### ビルド

```bash
# 本番用ビルド
npm run build

# SPA + SSR ビルド（オプション）
npm run build:ssr
```

## 🔐 セキュリティ機能

- **CSRF保護**: Laravel標準のCSRF保護
- **2要素認証**: Fortifyの2FA機能
- **パスワードハッシング**: bcryptハッシング
- **ShadowBox**: カート・注文データは認証ユーザーに限定

## 📦 次のステップ

- [ ] Stripe統合（決済処理）
- [ ] 注文履歴ページ
- [ ] 支払い処理ワークフロー
- [ ] 商品管理画面（管理者機能）
- [ ] メール通知（注文確認等）
- [ ] 在庫管理の強化
- [ ] キャッシング最適化
- [ ] API エンドポイント作成

## 📄 ライセンス

MIT License

## 👨‍💻 開発者向け情報

### コード品質チェック

```bash
# ESLint チェック・修正
npm run lint

# Prettier フォーマット確認・修正
npm run format
npm run format:check

# TypeScript 型チェック
npm run types

# PHP コード品質チェック
./vendor/bin/pint
```

### テスト実行

```bash
# PHPUnit テスト実行
php artisan test

# 特定のテストスイートを実行
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
```

### データベース操作

```bash
# マイグレーション実行
php artisan migrate

# ロールバック
php artisan migrate:rollback

# シード実行（初期データ投入）
php artisan db:seed
```

---

*Made with ❤️ for modern e-commerce applications*