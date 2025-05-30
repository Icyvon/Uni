document.addEventListener('DOMContentLoaded', () => {
    // Accordion
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', () => {
            const accordionItem = header.parentElement;
            const accordionContent = header.nextElementSibling;
            const isActive = accordionItem.classList.contains('active');

            document.querySelectorAll('.accordion-item').forEach(item => {
                item.classList.remove('active');
                item.querySelector('.accordion-content').classList.remove('active');
                item.querySelector('.accordion-header').classList.remove('active');
            });

            if (!isActive) {
                accordionItem.classList.add('active');
                accordionContent.classList.add('active');
                header.classList.add('active');
            }
        });
    });

    // Tabs
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            button.classList.add('active');
            const tabId = button.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    const mainContent = document.querySelector('.main-content');
    const toggleButton = document.querySelector('.sidebar-toggle');

    toggleButton.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        sidebar.classList.toggle('active');
        mainContent.classList.toggle('sidebar-collapsed');
    });

    // Close sidebar on mobile when clicking outside
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 768 && sidebar.classList.contains('active') && !sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
            sidebar.classList.remove('active');
            sidebar.classList.add('collapsed');
            mainContent.classList.add('sidebar-collapsed');
        }
    });
});