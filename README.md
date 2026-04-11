Aerostat は、
羽毛原料の価格シミュレーションから契約・出荷管理までを一元化したWebアプリケーションです。

価格判断の迅速化と、契約・在庫状況の可視化による業務効率化を目的として開発しました。

🚀 主な機能

📊 価格シミュレーション

ドル単価 × 為替レートから販売価格を自動計算
ダウン比率ごとの価格一覧表示
利益率変更によるリアルタイム再計算
PDF出力（単体 / 一括）

🧾 契約管理

得意先ごとの契約登録
シーズン / 羽毛種 / 産地 / ダウン比率の管理
契約数量・単価の登録

🚚 出荷管理

出荷数量の入力
残在庫・進捗率の自動計算
契約ごとのデリバリー状況を可視化

📈 その他

価格履歴管理
得意先管理（CRUD）
モダンUI（Tailwind CSS）

🛠 技術スタック

Backend: Laravel 12 / PHP 8.5
Frontend: Blade / Tailwind CSS
Database: MySQL

技術選定理由

Laravel：開発効率と保守性を重視
Blade + Tailwind：軽量かつ高速にUI構築
MySQL：実務でも一般的な構成を採用

📸 スクリーンショット

① ダッシュボード
<img width="2660" height="2240" src="https://github.com/user-attachments/assets/62a49a25-5644-48b6-94c8-62261bf9284e" />

② 価格計算
<img width="2660" height="1964" src="https://github.com/user-attachments/assets/b2c64523-510a-4c2c-b31d-d20994fe6edf" />

③ 価格推移
<img width="2660" height="2388" src="https://github.com/user-attachments/assets/e809f1f5-dccc-4948-ae78-7a0fcd77070d" />

④ 得意先管理
<img width="2660" height="1964" src="https://github.com/user-attachments/assets/b189b28d-ec97-4863-b65a-77b067eed39c" />

⑤ 履歴
<img width="2660" height="4244" src="https://github.com/user-attachments/assets/556ebed8-bfb6-493f-bf70-28891df671d9" />

⚙️ セットアップ方法

git clone https://github.com/your-username/aerostat.git
cd aerostat

cp .env.example .env
composer install
php artisan key:generate

# DB設定を.envで行ってください
php artisan migrate

php artisan serve
---

🔐 ログインについて

Laravel Breeze（または認証機能）を使用しています。
ユーザー登録後にログインしてください。

---

📂 主な構成

app/
 ├── Http/Controllers/
 │     ├── SimulatorController.php
 │     ├── CustomerController.php
 │     └── PriceChartController.php

resources/views/
 ├── simulator/
 ├── customers/
 └── price-chart/

---

🎯 工夫したポイント

シミュレーションと契約管理を一体化
モーダル＋リアルタイム計算でUX向上
実務を意識したデータ設計
一括PDF出力による業務効率化

🧩 今後の改善予定

グラフ機能の強化
CSVエクスポート
権限管理（ユーザーごと）
API化（フロント分離）

👨‍💻 開発者
GitHub: https://github.com/itsuki3102pj22

📄 ライセンス
MIT License
