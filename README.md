## 🔐 Fitur Login Google OAuth, Konfirmasi Email, dan Lupa Password via SMTP di CodeIgniter 4

Proyek ini merupakan implementasi sistem autentikasi lengkap menggunakan **CodeIgniter 4**, mencakup:
- 🔐 **Login dengan akun Google (OAuth 2.0)**
- 📧 **Konfirmasi email saat registrasi**
- 🔁 **Fitur reset password via email (lupa password)**  
Menggunakan integrasi SMTP (Gmail) untuk pengiriman email, serta Google API Client untuk proses OAuth.

### 📦 Instalasi Project

1. **Clone Repository**

   ```bash
   git clone https://github.com/username/nama-repo.git
   cd nama-repo
   ```

2. **Install Dependency**

   ```bash
   composer install
   ```

3. **Salin File `.env` dan Konfigurasi**

   ```bash
   cp env .env
   ```

   Lalu edit file `.env` ganti konfigurasi database dan sesuaikan konfigurasi berikut:

   ```env
   app.baseURL = 'http://localhost:8080/'

   # Email Config
   EMAIL_FROM_EMAIL="noreply@domain.com"
   EMAIL_FROM_NAME="Aplikasi Login"
   SMTP_HOST="smtp.gmail.com"
   SMTP_USER="emailkamu@gmail.com"
   SMTP_PASS="xxxx xxxx xxxx xxxx"
   SMTP_PORT=587
   SMTP_TIMEOUT=5
   SMTP_CRYPTO="tls"

   # Google OAuth Config
   GOOGLE_CLIENT_ID="isi_client_id_dari_google"
   GOOGLE_CLIENT_SECRET="isi_client_secret_dari_google"
   GOOGLE_REDIRECT_URI="http://localhost:8080/auth/google/callback"
   ```

4. **Jalankan Migrasi Database**

   ```bash
   php spark migrate
   ```

5. **Jalankan Server**
   ```bash
   php spark serve
   ```

---

### ⚙️ Setup Google OAuth

1. Buka [Google Cloud Console](https://console.cloud.google.com)

2. Buat atau pilih Project

3. Aktifkan **OAuth Consent Screen**

   - Pilih **External**
   - Isi nama aplikasi, email support, dll
   - Tambahkan scope: `email`, `profile`

4. Buat **Credential** → OAuth Client ID

   - Application type: **Web Application**
   - Authorized Redirect URI:
     ```
     http://localhost:8080/auth/google/callback
     ```

5. Catat **Client ID** dan **Client Secret** → Masukkan ke file `.env`

## 🧠 Perhatikan Hal Berikut

✅ **SSL Certificate** (jika error cURL error 60)

1.  **Download** `cacert.pem` dari: [https://curl.se/ca/cacert.pem](https://curl.se/ca/cacert.pem)

2.  **Edit `php.ini`**:

    ```ini
    curl.cainfo = "C:\php\extras\ssl\cacert.pem"
    openssl.cafile = "C:\php\extras\ssl\cacert.pem"
    ```

3.  **Restart Apache** setelah perubahan.

---
