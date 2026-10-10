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
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.45;
        }
        .header-title {
            text-align: center;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .header-title h1 {
            font-size: 13pt;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-title p {
            font-size: 8.5pt;
            color: #64748b;
            margin: 4px 0 0 0;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .main-table > tbody > tr > td {
            border: 1.5px solid #334155;
            vertical-align: top;
            padding: 8px 10px;
        }
        .section-label {
            width: 20%;
            font-weight: bold;
            font-size: 10pt;
            color: #0f172a;
            background-color: #f8fafc;
        }
        .section-content {
            width: 80%;
        }

        .inner-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
        }
        .inner-table th {
            background-color: #f1f5f9;
            color: #0f172a;
            font-weight: bold;
            font-size: 8.5pt;
            border: 1px solid #475569;
            padding: 5px 6px;
            text-align: left;
        }
        .inner-table td {
            border: 1px solid #475569;
            padding: 5px 6px;
            font-size: 8pt;
            vertical-align: top;
        }

        .table-caption {
            font-weight: bold;
            font-size: 8.5pt;
            text-align: center;
            margin: 4px 0 10px 0;
            color: #0f172a;
        }

        .text-block {
            font-size: 8.5pt;
            text-align: justify;
            margin-bottom: 8px;
            color: #334155;
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
        <h1>TYK INVENTORY & POS MANAGEMENT SYSTEM</h1>
        <p>AWS EC2 Cloud Deployment Documentation &bull; Data Governance, Security Policy & Threat Modeling</p>
    </div>

    <table class="main-table">
        <tbody>
            <!-- DATA LIFECYCLE -->
            <tr>
                <td class="section-label">Data Lifecycle</td>
                <td class="section-content">
                    <p style="margin-top: 0; font-weight: bold;">Explain what happens to the data in each stage of the Data Lifecycle.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Lifecycle Stage</th>
                                <th style="width: 75%;">What Happens to the Data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Creation</strong></td>
                                <td>Cashiers, Inventory Managers, and Admins input data via the web interface (adding bicycle products/SKUs, recording stock-in shipments, and ringing up retail POS checkout transactions). System activity events and transactional records are generated automatically.</td>
                            </tr>
                            <tr>
                                <td><strong>Storage</strong></td>
                                <td>Transactional and relational business records are stored on the EC2 instance's block storage volume (Amazon EBS) within the SQLite database file (<code>database.sqlite</code>). Binary objects, generated report PDFs, and automated database backup snapshots are stored in an Amazon S3 bucket. Sensitive user credentials (passwords) are irreversibly hashed using <code>bcrypt</code>.</td>
                            </tr>
                            <tr>
                                <td><strong>Usage</strong></td>
                                <td>The Laravel backend application on Amazon EC2 queries stock quantities, executes atomic inventory decrements upon POS checkout completion, calculates cash change due, and aggregates financial analytics (Gross Revenue, COGS, Net Profit) presented on live dashboards.</td>
                            </tr>
                            <tr>
                                <td><strong>Sharing</strong></td>
                                <td>Authorized users access processed data through their role-based views. Printable customer sales invoices, POS thermal slips, and official business reports (PDF/CSV) are retrieved from Amazon S3 / local storage and transmitted securely to clients over HTTPS (TLS encryption).</td>
                            </tr>
                            <tr>
                                <td><strong>Archiving</strong></td>
                                <td>Past transaction ledgers, closed sales orders, and automated database snapshots (<code>.sqlite</code> / SQL dumps) are packaged periodically and transferred to Amazon S3 Standard-IA (Infrequent Access) or S3 Glacier using automated lifecycle rules for cost-effective disaster recovery.</td>
                            </tr>
                            <tr>
                                <td><strong>Deletion</strong></td>
                                <td>Soft-deleted records and obsolete temporary records are permanently purged from database tables using parameterized SQL queries. Expired archive objects and transient PDF exports stored in Amazon S3 are automatically removed based on designated bucket lifecycle expiration policies.</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="table-caption">Table 1: Data Lifecycle Matrix</div>

                    <div class="text-block">
                        The Data Lifecycle Matrix shows how data moves through the system from initial input to final deletion. Data is first collected through the frontend, stored securely in the local application database (Amazon EBS) and cloud object storage (Amazon S3), processed by the backend on EC2, and accessed by authorized users. Security controls such as IAM roles, HTTPS (TLS), and restricted bucket and database access protect the data at each stage. Retention policies ensure that data is not stored indefinitely, reducing storage costs and security risks.
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
                    <p style="margin-top: 0; font-weight: bold;">Identify IAM users and the policies and permissions set for them with justification on why those policies and permissions were granted.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 25%;">IAM Entity</th>
                                <th style="width: 35%;">Policy Attached</th>
                                <th style="width: 40%;">Justification</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>EC2 Backend Role</strong><br><span style="font-size: 7.5pt; color: #64748b;">(Instance Profile)</span></td>
                                <td>
                                    <code>AmazonSSMManagedInstanceCore</code>,<br>
                                    Custom S3 Policy:<br>
                                    <code>s3:PutObject</code>, <code>s3:GetObject</code>, <code>s3:ListBucket</code> (scoped to designated backup bucket)
                                </td>
                                <td>
                                    Allows the backend application running on EC2 to securely upload, retrieve, and list files and database backups in the designated S3 bucket without hardcoding AWS credentials in <code>.env</code>. Follows the Principle of Least Privilege by restricting access to one bucket only.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>DevOps / Administrator User</strong></td>
                                <td>
                                    <code>AmazonEC2FullAccess</code>,<br>
                                    <code>AmazonS3FullAccess</code>,<br>
                                    <code>IAMReadOnlyAccess</code>
                                </td>
                                <td>
                                    Required to provision and manage infrastructure components including EC2 instances, S3 buckets, security groups, and Nginx/SSL configuration. Permissions are granted strictly for deployment, maintenance, and administrative configuration.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Auditor User</strong><br><span style="font-size: 7.5pt; color: #64748b;">(Optional / Evaluator)</span></td>
                                <td>
                                    <code>ReadOnlyAccess</code>
                                </td>
                                <td>
                                    Allows academic panelists and auditors to monitor and evaluate cloud architecture, instance health, S3 storage, and security groups without the ability to modify or delete infrastructure. Ensures security oversight while preventing accidental changes.
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
                    <p style="margin-top: 0; font-weight: bold;">Using the STRIDE model, identify possible vulnerabilities in your application and deployment strategy as well as mitigations to prevent exposure of data.</p>

                    <table class="inner-table">
                        <thead>
                            <tr>
                                <th style="width: 20%;">Threat</th>
                                <th style="width: 18%;">Vulnerability</th>
                                <th style="width: 62%;">Justification & Mitigation</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Spoofing</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    If authentication is not properly implemented, attackers could impersonate legitimate cashiers or administrators. However, enforced HTTPS/TLS encryption via Certbot, bcrypt password hashing, session regeneration, and SSH key pairs (<code>.pem</code>) mitigate credential spoofing at application and infrastructure levels.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tampering</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Data in transit could be modified if HTTPS is not enforced between client and backend. S3 objects or database records could also be altered if bucket policies are misconfigured. Proper TLS certificates, restricted IAM bucket policies, CSRF token verification, and atomic database transactions (<code>DB::transaction</code>) mitigate this risk.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Repudiation</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Without proper logging (CloudTrail, application logs, Nginx logs), users or attackers could deny performing actions (e.g., unauthorized stock adjustments, file uploads, or deletions). The application features an immutable <code>ActivityLog</code> audit trail, while Nginx and AWS system logs provide non-repudiation.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Information Disclosure</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    Misconfigured S3 bucket permissions or exposed environment variables (<code>.env</code>) could leak sensitive data. Setting Nginx web root strictly to <code>/public</code>, using private S3 buckets and IAM roles instead of access keys, applying <code>chmod 664</code> to database files, and role-based field masking significantly lower exposure.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Denial of Service (DoS)</strong></td>
                                <td class="badge-high">High</td>
                                <td>
                                    The EC2 instance (especially <code>t2.micro</code> free tier with 1 vCPU and 1 GB RAM) could be overwhelmed by high traffic or malicious requests. Without rate limiting, autoscaling, or AWS WAF, the backend is susceptible to disruption. Nginx rate limiting (<code>limit_req_zone</code>), request buffers, and pre-compiled assets (<code>npm run build</code>) mitigate server disruption.
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Elevation of Privilege</strong></td>
                                <td class="badge-low">Low</td>
                                <td>
                                    If IAM roles follow least privilege and no root credentials are exposed, privilege escalation risk is minimal. Server-side route middleware (<code>role:admin</code>) and unprivileged Linux execution (<code>www-data</code> / <code>ubuntu</code>) prevent vertical privilege escalation, while avoiding overly permissive IAM policies ensures minimal exposure.
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
