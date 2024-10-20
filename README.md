   
## Tələblər

    - PHP: ^7.3|^8.0
    - Composer: 2.x
    - Laravel: ^8.75
    - MySQL: 5.7 və ya daha yeni versiya

**Repozitordan Layihəni Klonlayın**:
    git clone https://github.com/tunar036/atm.git
    cd atm

    composer install

    cp .env.example .env
## env file-ye düzəlişlər edin edin

   php artisan migrate

   php artisan db:seed

   php artisan ser