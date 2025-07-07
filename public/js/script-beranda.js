 function detail(id) {
    window.location.href = '/gedung/' + id;
}

// Modal Controls
document.addEventListener('DOMContentLoaded', function() {
    // Filter Modal
    const filterBtn = document.getElementById('filterBtn');
    const filterModal = document.getElementById('filterModal');
    const closeFilterModal = document.getElementById('closeFilterModal');
    
    filterBtn.addEventListener('click', () => {
        filterModal.classList.add('active');
    });
    
    closeFilterModal.addEventListener('click', () => {
        filterModal.classList.remove('active');
    });
    
    // Calendar Modal
    const calendarBtn = document.getElementById('calendarBtn');
    const calendarModal = document.getElementById('calendarModal');
    const closeCalendarModal = document.getElementById('closeCalendarModal');
    const closeCalendarBtn = document.getElementById('closeCalendarBtn');
    
    calendarBtn.addEventListener('click', () => {
        calendarModal.classList.add('active');
    });
    
    closeCalendarModal.addEventListener('click', () => {
        calendarModal.classList.remove('active');
    });
    
    closeCalendarBtn.addEventListener('click', () => {
        calendarModal.classList.remove('active');
    });
    
    // Reset filter
    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('filterForm').reset();
    });
    
    // Set nilai filter dari URL
    const urlParams = new URLSearchParams(window.location.search);
    document.querySelector('select[name="daerah"]').value = urlParams.get('daerah') || '';
    document.querySelector('select[name="kategori"]').value = urlParams.get('kategori') || '';
    document.querySelector('input[name="kapasitas"]').value = urlParams.get('kapasitas') || '';
    
    // Auto-search
    const searchInput = document.querySelector('input[name="search"]');
    let searchTimer;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            document.getElementById('searchForm').submit();
        }, 500);
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', (e) => {
        if (e.target === filterModal) {
            filterModal.classList.remove('active');
        }
        if (e.target === calendarModal) {
            calendarModal.classList.remove('active');
        }
    });
});