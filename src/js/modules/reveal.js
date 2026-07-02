/**
 * Great Endured Technology — Scroll entrance reveal handler
 * Spec from Resource.md (Section 5.2)
 */

export function initScrollReveal() {
  const ioOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.15 // Triggers when 15% of element is visible
  };

  const io = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        // Once visible, we can unobserve if we only want it to reveal once
        observer.unobserve(entry.target);
      }
    });
  }, ioOptions);

  document.querySelectorAll('[data-reveal]').forEach(el => {
    io.observe(el);
  });
}
export default initScrollReveal;
