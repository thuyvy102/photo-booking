document.addEventListener('DOMContentLoaded', function() {
    // Tìm ô input có name là 'date' hoặc type là 'date'
    const dateInput = document.querySelector('input[name="date"]');
    
    if (dateInput) {
        const today = new Date();
        // Cộng thêm 1 ngày
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);

        // Định dạng thành chuỗi YYYY-MM-DD
        const yyyy = tomorrow.getFullYear();
        const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
        const dd = String(tomorrow.getDate()).padStart(2, '0');
        const minDate = `${yyyy}-${mm}-${dd}`;

        // Gán vào thuộc tính min của input
        dateInput.setAttribute('min', minDate);
    }
});