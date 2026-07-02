<?php
/**
 * Great Endured Technology — Contact Page Template
 * Governed by RBP (AGENTS.md)
 */

declare(strict_types=1);

// Capture query param to preset dropdown (e.g. /contact?service=wordpress)
$presetService = $_GET['service'] ?? '';
?>

<section class="section-padding" style="padding-top: calc(var(--header-height) + 60px);">
    <div class="container">
        <div class="section-header" data-reveal>
            <span class="hero-tagline" style="box-shadow: none;">Get In Touch</span>
            <h1 style="margin-top: 15px;">Start Your Project</h1>
            <p>Ready to build something enduring? Drop us a line and let's craft your solution.</p>
        </div>

        <div class="grid grid-2" style="margin-top: 50px; align-items: start; gap: 50px;">
            <!-- Contact Form (Submits to api.php handler via AJAX) -->
            <div class="card" data-reveal style="padding: 40px;">
                <h3 style="margin-bottom: 25px;">Send a Message</h3>
                
                <!-- Feedback Alerts -->
                <div class="form-alert form-alert-success" id="form-success"></div>
                <div class="form-alert form-alert-error" id="form-error"></div>

                <form action="/api/contact" method="POST" id="contact-form">
                    <!-- CSRF Protection Field -->
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" name="name" id="name" class="form-input" placeholder="e.g. John Doe" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" class="form-input" placeholder="e.g. john@company.com" required>
                    </div>

                    <div class="form-group">
                        <label for="service" class="form-label">Interest Service</label>
                        <select name="service" id="service" class="form-input" style="background-color: var(--color-ink);">
                            <option value="general" <?php echo $presetService === 'general' ? 'selected' : ''; ?>>General Inquiry / Branding</option>
                            <option value="wordpress" <?php echo $presetService === 'wordpress' ? 'selected' : ''; ?>>WordPress & Elementor Site</option>
                            <option value="php" <?php echo $presetService === 'php' ? 'selected' : ''; ?>>PHP Custom System</option>
                            <option value="seo" <?php echo $presetService === 'seo' ? 'selected' : ''; ?>>SEO & Positioning</option>
                            <option value="marketing" <?php echo $presetService === 'marketing' ? 'selected' : ''; ?>>Digital Marketing (Ads)</option>
                            <option value="content" <?php echo $presetService === 'content' ? 'selected' : ''; ?>>Canva & 3D AI Mockups</option>
                            <option value="ecommerce" <?php echo $presetService === 'ecommerce' ? 'selected' : ''; ?>>E-commerce Startup</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message" class="form-label">Project Details / Message</label>
                        <textarea name="message" id="message" class="form-input" placeholder="Briefly describe your goals, timeline, and expectations..." required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Submit Request</button>
                </form>
            </div>

            <!-- Context / Info Sidebar -->
            <div style="display: flex; flex-direction: column; gap: 30px;" data-reveal data-delay="200">
                <div class="card">
                    <h3 style="margin-bottom: 20px; color: var(--color-cyan-pulse);">Contact Information</h3>
                    <p style="margin-bottom: 20px;">For general questions, sales, or partnerships, feel free to reach out directly via email. We usually respond within 12–24 hours.</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="color: var(--color-cyan-pulse);">
                                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.9rem; color: var(--color-mist);">Email Us</h4>
                                <a href="mailto:contact@greatentech.com" style="color: var(--color-white); font-weight: 500;">contact@greatentech.com</a>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="color: var(--color-cyan-pulse);">
                                <svg style="width: 24px; height: 24px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.9rem; color: var(--color-mist);">Location</h4>
                                <p style="color: var(--color-white); font-weight: 500; margin: 0;">Dhaka, Bangladesh</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="border-color: rgba(59, 130, 246, 0.15);">
                    <h3 style="margin-bottom: 15px; color: var(--color-blue-glow);">Our Build Process</h3>
                    <p style="font-size: 0.9rem; color: var(--color-fog); line-height: 1.6; margin: 0;">Once we receive your submission, our team maps out a technical proposal and visual wireframe layout for your assessment. We don't write generic layouts — each contract gets a customized implementation plan and timeline mapping.</p>
                </div>
            </div>
        </div>
    </div>
</section>
