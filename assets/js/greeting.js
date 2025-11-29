function getLocalTimeBasedGreeting() {
  const date = new Date();
  const hour = date.getHours();
  let greeting;

  // (Calculation logic remains here...)
  if (hour >= 5 && hour < 12) {
    greeting = "Good morning";
  } else if (hour >= 12 && hour < 17) {
    greeting = "Good afternoon";
  } else if (hour >= 17 && hour < 23) {
    greeting = "Good evening";
  } else {
    greeting = "In dreamland. Do not disturb. 😴";
  }

  const greetingElement = document.getElementById('time-based-greeting');

  if (greetingElement) {
    // 1. Set up the observer BEFORE setting the content
    const observer = new MutationObserver(mutationsList => {
      for (const mutation of mutationsList) {
        if (mutation.type === 'childList') {
          // Check if the element was removed
          mutation.removedNodes.forEach(node => {
            if (node === greetingElement) {
              console.error("❌ ERROR: The greeting element was REMOVED from the DOM!");
              observer.disconnect();
            }
          });
        } else if (mutation.type === 'characterData' && greetingElement.textContent === "") {
          // Check if the text content was cleared
          console.error("❌ ERROR: The greeting content was CLEARED by an external script!");
          observer.disconnect();
        }
      }
    });

    // Start observing the element for content and attribute changes
    observer.observe(greetingElement, {
      childList: true,
      subtree: true,
      characterData: true,
      attributes: true
    });

    // 2. Set the greeting immediately
    greetingElement.textContent = greeting;

    // greetingElement.classList.add(
    //   'my-4',
    //   'text-2xl',
    //   'ml-[clamp(-12px,calc((100vw-350px)*-.009),0px)]',
    //   'font-light',
    //   'text-gray12',
    //   'transition-colors', // Separated
    //   'duration-250',     // Separated
    //   'ease-in',        // Separated
    //   'leading-snug'
    // );

    console.log(`✅ Greeting set to: ${greeting}. Monitoring for conflicts.`);
  }
}

// Run the script
getLocalTimeBasedGreeting();
