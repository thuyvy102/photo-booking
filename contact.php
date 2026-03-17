<?php include 'header.php'; ?>

<main class="contact-container">
    <section class="contact-header section-padding">
        <div class="content-limit">
            <h1 class="main-title">LIÊN HỆ</h1>
            <p class="subtitle">Hãy để Light Studio đồng hành cùng câu chuyện của bạn</p>
            <div class="divider"></div>
        </div>
    </section>

    <section class="contact-content section-padding">
        <div class="content-limit grid-2">
            <div class="contact-info">
                <h3>THÔNG TIN KẾT NỐI</h3>
                <p class="info-desc">Chúng tôi luôn sẵn sàng lắng nghe và tư vấn những ý tưởng độc bản dành riêng cho bạn.</p>
                
                <ul class="contact-details">
                    <li>
                        <span class="icon">✦</span>
                        <div>
                            <strong>Địa chỉ:</strong>
                            <p>Toà nhà Light Studio, Quận Ninh Kiều, TP. Cần Thơ</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon">✦</span>
                        <div>
                            <strong>Hotline:</strong>
                            <p>090x xxx xxx (8:00 - 21:00 hàng ngày)</p>
                        </div>
                    </li>
                    <li>
                        <span class="icon">✦</span>
                        <div>
                            <strong>Email:</strong>
                            <p>yahsightlee@gmail.com</p>
                        </div>
                    </li>
                </ul>

                <div class="social-links">
                    <strong>FOLLOW US:</strong>
                    <div class="social-icons">Facebook / Instagram / Pinterest</div>
                </div>
            </div>

            <div class="contact-form-wrapper" id="contact-form">
                <h3>GỬI TIN NHẮN</h3>

                <?php if(isset($_GET['send']) && $_GET['send'] == 'success'): ?>
                    <div class="success-message" style="background-color: #f8f5f2; border: 1px solid #d4c4b7; color: #8c7867; padding: 15px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; border-radius: 2px;">
                        <span style="display: block; margin-bottom: 5px; font-size: 1.2rem;">✦</span>
                        Cảm ơn bạn! Yêu cầu của bạn đã được gửi thành công. <br> Light Studio sẽ liên hệ lại sớm nhất.
                    </div>
                <?php endif; ?>
                <form action="ajax/contact_process.php" method="POST" class="main-form">
                    <div class="form-group">
                        <input type="text" name="fullname" placeholder="Họ và tên của bạn" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Địa chỉ Email" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" placeholder="Số điện thoại">
                    </div>
                    <div class="form-group">
                        <textarea name="message" rows="5" placeholder="Bạn đang quan tâm đến gói chụp nào?"></textarea>
                    </div>
                    <button type="submit" class="btn-aesthetic">
                        <span>GỬI YÊU CẦU</span>
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 7.5H14M14 7.5L8 1.5M14 7.5L8 13.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="final-statement section-padding">
        <div class="content-limit center-align">
            <p class="final-quote">Hơn cả một tấm hình đẹp, đó là trải nghiệm. Chúng tôi đồng hành cùng bạn từ khâu lên ý tưởng cho đến những bản in hoàn thiện nhất.</p>
        </div>
    </section>
</main>

<?php if(file_exists('footer.php')) include 'footer.php'; ?>