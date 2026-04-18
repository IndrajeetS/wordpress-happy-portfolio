document.addEventListener('DOMContentLoaded', function() {
    const faqButtons = document.querySelectorAll('.faq-button');

    faqButtons.forEach(button => {
        button.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

            // Optional: Close all other accordions (uncomment if desired)
            /*
            document.querySelectorAll('.faq-item').forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('is-open');
                    item.querySelector('.faq-button').setAttribute('aria-expanded', 'false');
                    item.querySelector('.faq-content').setAttribute('aria-hidden', 'true');
                }
            });
            */

            // Toggle current item
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                faqItem.classList.remove('is-open');
                faqItem.querySelector('.faq-content').setAttribute('aria-hidden', 'true');
            } else {
                this.setAttribute('aria-expanded', 'true');
                faqItem.classList.add('is-open');
                faqItem.querySelector('.faq-content').setAttribute('aria-hidden', 'false');
            }
        });
    });
});
