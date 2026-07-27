# Ku (空) — KUKAI (空界)

**A Collective AI Platform for Synchronized Institutional Disconnection**

KU is an academic ecosystem designed to solve the structural impossibility of disconnection in a hyperconnected university environment. Rather than putting the burden of "going offline" on the individual (which creates a Fear Of Missing Out or academic penalties), KU synchronizes the entire institution into offline windows simultaneously. When everyone enters silence together, there is nothing to miss.

## Features

- **Institution-Side AI Engine:** Analyzes the digital ecosystem to identify optimal weekly offline windows.
- **Synchronized Silence:** LMS notifications are suspended, deadlines are frozen, emails are queued, and campus WiFi deprioritizes social media during active windows.
- **Faculty Shield:** Automatically queues incoming communications during windows, protecting faculty off-hours while ensuring all students receive responses simultaneously when the window ends.
- **FOMO Risk Diagnostics:** Admin tools to monitor the institutional pressure level (based on deadline clustering, midnight activity, etc.).
- **Research Data Portal:** Provides anonymized datasets for researchers to study the impact of digital synchronization on student anxiety and cognitive recovery.

## User Roles & Test Accounts

The system is pre-seeded with test accounts for each institutional role. All accounts use the password `password`.

1. **Platform Administrator**
   - **Email:** `admin@ku.edu`
   - **Access:** `/admin/dashboard`
   - **Capabilities:** Manage global offline windows, trigger the AI Simulator, review the FOMO Risk Index, and process student accommodation requests.

2. **Faculty Member**
   - **Emails:** `santos@ku.edu`, `reyes@ku.edu`
   - **Access:** `/faculty/dashboard`
   - **Capabilities:** Manage Faculty Shield auto-replies, view personal recovery analytics, and access the held message queue.

3. **Institutional Researcher**
   - **Email:** `researcher@ku.edu`
   - **Access:** `/research/dashboard`
   - **Capabilities:** Access anonymized datasets measuring Cognitive Recovery Index (CRI) improvements and apply for dataset access.

## Installation & Setup

1. **Clone the repository** (or download the source).
2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```
3. **Set up the environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. **Configure Database (SQLite by default):**
   Ensure your `.env` is configured for SQLite or your preferred database. 
5. **Run Migrations & Seed the Database:**
   *This is required to generate the users, windows, and analytics data.*
   ```bash
   php artisan migrate --seed
   ```
6. **Start the Development Servers:**
   ```bash
   # In one terminal:
   php artisan serve

   # In a second terminal:
   npm run dev
   ```
7. **Open the Application:**
   Navigate to `http://localhost:8000` (or your configured `APP_URL`) in your browser to view the presentation landing page, and log in to explore the different dashboards.

## Architecture

- **Backend:** Laravel 11 (PHP 8.3+)
- **Frontend:** Blade templating, TailwindCSS, Alpine.js
- **Database:** SQLite / PostgreSQL / MySQL (configurable)
