window.initAccordion = function() {
    const faqButtons = document.querySelectorAll('.faq-button');

    faqButtons.forEach(button => {
        if (button.dataset.accordionInit) return;
        button.dataset.accordionInit = "true";

        button.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isExpanded = this.getAttribute('aria-expanded') === 'true';

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
};

document.addEventListener('DOMContentLoaded', window.initAccordion);
