<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cloud Architecture, Data Lifecycle, Policy Matrix & STRIDE Threat Model</title>
    <style>
        @page {
            margin: 25px 30px;
        }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            color: #1e293b;
            line-height: 1.4;
        }
        .header-title {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header-title h1 {
            font-size: 12pt;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title p {
            font-size: 8pt;
            color: #64748b;
            margin: 3px 0 0 0;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .main-table > tbody > tr > td {
            border: 1.5px solid #334155;
            vertical-align: top;
            padding: 8px 10px;
        }
        .section-label {
            width: 22%;
            font-weight: bold;
            font-size: 9.5pt;
            color: #0f172a;
            background-color: #f8fafc;
        }
        .section-content {
            width: 78%;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
            margin: 6px 0;
        }
        .inner-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            font-size: 8pt;
            border: 1px solid #475569;
            padding: 4px 6px;
            text-align: left;
        }
        .inner-table td {
            border: 1px solid #475569;
            padding: 4px 6px;
            font-size: 7.8pt;
            vertical-align: top;
        }

        .table-caption {
            font-weight: bold;
            font-size: 8pt;
            text-align: center;
            margin: 4px 0 8px 0;
            color: #0f172a;
        }

        .text-block {
            font-size: 8pt;
            text-align: justify;
            margin-bottom: 6px;
            color: #334155;
            line-height: 1.35;
        }

        .badge-low {
            color: #15803d;
            font-weight: bold;
        }
        .badge-high {
            color: #b91c1c;
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="header-title">
        <h1>TYK INVENTORY &amp; POS MANAGEMENT SYSTEM</h1>
        <p>Cloud Architecture Documentation &bull; Vercel, Supabase &amp; Cloudinary Ecosystem</p>
    </div>

    <table class="main-table">
        <tbody>
            <!-- DATA LIFECYCLE -->
            <tr>
                <td class="section-label">Data Lifecycle</td>
                <td class="section-content">
                    <p style="margin-top: 0; font-weight: bold; margin-bottom: 4px;">Explain what happens to the data in each stage of the Data Lifecycle.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 22%;">Lifecycle Stage</th>
                                <th style="width: 78%;">What Happens to the Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Creation</strong></td>
                                <td>Cashiers, Inventory Managers, and Admins input data through the Vercel-hosted frontend web interface (creating bicycle products/SKUs, uploading product images, recording stock shipments, and processing retail POS checkout orders). System activity logs and customer sales records are automatically generated upon each event.</td>
                            </tr>
                            <tr>
                                <td><strong>Storage</strong></td>
                                <td>Transactional and relational business records are stored securely in a managed cloud PostgreSQL database hosted on <strong>Supabase</strong> (Singapore <code>ap-southeast-1</code>). Uploaded product media and shop assets are stored in <strong>Cloudinary</strong> cloud storage (<code>ihlkdz00</code>). Sensitive user passwords are irreversibly hashed using <code>bcrypt</code> (12 rounds) before database storage.</td>
                            </tr>
                            <tr>
                                <td><strong>Usage</strong></td>
                                <td>Serverless PHP execution runtimes on <strong>Vercel</strong> (Singapore <code>sin1</code>) query inventory levels, execute atomic stock decrements upon checkout completion, compute subtotal and 12% VAT calculations, and aggregate financial analytics (Gross Revenue, COGS, Net Profit) displayed on live dashboards. Database calls utilize Supabase's high-speed Transaction Pooler (Port 6543).</td>
                            </tr>
                            <tr>
                                <td><strong>Sharing</strong></td>
                                <td>Authorized users access processed data through strictly defined role-based interfaces. Generated customer sales invoices, receipt slips, and business summary reports (PDF/CSV) are delivered securely over HTTPS (TLS 1.3) via Vercel Edge CDN, while product images are served globally through Cloudinary CDN URLs.</td>
                            </tr>
                            <tr>
                                <td><strong>Archiving</strong></td>
                                <td>Past transaction ledgers, closed sales orders, and audit trail logs stored in Supabase are periodically backed up into compressed SQL dumps and CSV exports. Cloudinary automatically manages asset lifecycle tiers and applies automatic media caching and format optimization policies.</td>
                            </tr>
                            <tr>
                                <td><strong>Deletion</strong></td>
                                <td>Soft-deleted records and obsolete temporary session records are permanently purged from Supabase database tables using parameterized SQL queries with cascading referential integrity. Obsolete media files and product photos are permanently deleted via Cloudinary's Admin API (<code>UploadApi::destroy</code>).</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="table-caption">Table 1: Data Lifecycle Matrix</div>

                    <div class="text-block">
                        The Data Lifecycle Matrix shows how data moves through the system from initial input to final deletion. Data is first collected through the frontend, stored securely in cloud-managed PostgreSQL (Supabase) and media cloud storage (Cloudinary), processed by serverless backend runtimes on Vercel, and accessed by authorized users. Security controls such as scoped API keys, HTTPS (TLS 1.3), transaction poolers, and database connection authentication protect the data at each stage. Retention policies ensure that data is not stored indefinitely, reducing storage costs and security risks.
                    </div>

                    <div class="text-block" style="margin-bottom: 0;">
                        Overall, the system demonstrates basic cloud data governance principles, including access control, lifecycle awareness, and structured data flow. However, improvements such as automated lifecycle policies, enhanced logging, and monitoring could further strengthen security and reliability.
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="page-break"></div>

    <table class="main-table">
        <tbody>
            <!-- POLICY AND ACCESS MATRIX -->
            <tr>
                <td class="section-label">Policy and Access Matrix</td>
                <td class="section-content">
                    <p style="margin-top: 0; font-weight: bold; margin-bottom: 4px;">Identify IAM users and the policies and permissions set for them with justification on why those policies and permissions were granted.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 24%;">IAM Entity</th>
                                <th style="width: 36%;">Policy Attached</th>
                                <th style="width: 40%;">Justification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Vercel Backend Service</strong><br><span style="font-size: 7.2pt; color: #64748b;">(Serverless App Runtime)</span></td>
                                <td>
                                    Scoped Supabase Connection:<br>
                                    <code>postgres.pzsijwhcxtnaanlpwvaq</code> (Transaction Pooler, Port 6543)<br>
                                    Scoped Cloudinary Credentials:<br>
                                    <code>CLOUDINARY_URL</code> (Upload &amp; Read access)
                                </td>
                                <td>
                                    Allows the backend application running on Vercel serverless functions to securely execute transactional CRUD operations in Supabase and upload media to Cloudinary without exposing master administrator credentials to the client. Follows the Principle of Least Privilege by restricting database access to pooled transactions.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>DevOps / Administrator User</strong></td>
                                <td>
                                    <code>Vercel Project Admin</code>,<br>
                                    <code>Supabase Project Owner</code> (Full DDL/DML),<br>
                                    <code>Cloudinary Master Admin</code>
                                </td>
                                <td>
                                    Required to provision and manage infrastructure components including Vercel edge deployments, encrypted environment variables, Supabase schema migrations, connection pooling, and Cloudinary media settings. Permissions are granted strictly for deployment, maintenance, and administrative configuration.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Auditor User</strong><br><span style="font-size: 7.2pt; color: #64748b;">(Optional / Evaluator)</span></td>
                                <td>
                                    <code>ReadOnlyAccess</code><br>
                                    (Vercel Viewer, Supabase SQL/Table Viewer Read-Only)
                                </td>
                                <td>
                                    Allows academic panelists and auditors to monitor and evaluate cloud architecture, deployment history, database schemas, and media storage configurations without the ability to modify or delete live production resources. Ensures security oversight while preventing accidental changes.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <!-- THREAT MODEL (STRIDE) -->
            <tr>
                <td class="section-label">Threat Model</td>
                <td class="section-content">
                    <p style="margin-top: 0; font-weight: bold; margin-bottom: 4px;">Using the STRIDE model, identify possible vulnerabilities in your application and deployment strategy as well as mitigations to prevent exposure of data.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Threat</th>
                                <th style="width: 17%;">Vulnerability</th>
                                <th style="width: 63%;">Justification &amp; Mitigation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Spoofing</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    If authentication is not properly implemented, attackers could impersonate legitimate cashiers or administrators. However, enforced HTTPS on Vercel, bcrypt password hashing (12 rounds), session regeneration, and scoped database credentials mitigate credential spoofing at application and infrastructure levels.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tampering</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Data in transit could be modified if HTTPS is not enforced between client and backend. Supabase database records or Cloudinary media could also be altered if credentials leak. Proper TLS 1.3 certificates on Vercel, CSRF token verification, parameterized queries, and atomic database transactions (<code>DB::transaction</code>) mitigate this risk.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Repudiation</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Without proper logging, users or attackers could deny performing actions (e.g., unauthorized stock adjustments, order voids, or file uploads). The application features an immutable <code>ActivityLog</code> audit trail in Supabase, while Vercel runtime logs and Cloudinary access logs provide non-repudiation.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Information Disclosure</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Exposed environment variables or open database ports could leak sensitive data. Storing sensitive credentials in Vercel's Encrypted Environment Secrets (<code>APP_KEY</code>, <code>DB_PASSWORD</code>, <code>CLOUDINARY_URL</code>), VPC connection pooling on Supabase, and role-based view masking significantly lower exposure.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Denial of Service (DoS)</strong></td>
                                <td class="badge-high">High</td>
                                <td>
                                    High burst traffic or malicious request flooding could exhaust serverless function execution limits on Vercel or overwhelm the connection pool on Supabase's free tier. Vercel Edge DDoS protection, in-memory caching (<code>Cache::remember</code>), optimized grouped queries, and Supabase Transaction Pooling mitigate service disruption.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Elevation of Privilege</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    If cloud service roles follow least privilege and no master API secrets are exposed to the client, privilege escalation risk is minimal. Server-side Laravel Role-Based Access Control (RBAC) middleware (<code>admin</code>, <code>inventory_manager</code>, <code>cashier</code>) strictly verifies permissions at every endpoint, preventing unauthorized vertical escalation.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>
