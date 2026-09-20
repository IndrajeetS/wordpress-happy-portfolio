function getLocalTimeBasedGreeting() {
  const date = new Date();
  const hour = date.getHours();
  let greeting;

  if (typeof happyGreetings !== 'undefined') {
    if (hour >= 5 && hour < 12) {
      greeting = happyGreetings.morning;
    } else if (hour >= 12 && hour < 17) {
      greeting = happyGreetings.afternoon;
    } else if (hour >= 17 && hour < 23) {
      greeting = happyGreetings.evening;
    } else {
      greeting = happyGreetings.night;
    }
  } else {
    if (hour >= 5 && hour < 12) {
      greeting = "Good morning";
    } else if (hour >= 12 && hour < 17) {
      greeting = "Good afternoon";
    } else if (hour >= 17 && hour < 23) {
      greeting = "Good evening";
    } else {
      greeting = "In dreamland. Do not disturb. 😴";
    }
  }

  const greetingElement = document.getElementById("time-based-greeting");

  if (greetingElement) {
    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        if (mutation.type === "childList") {
          mutation.removedNodes.forEach((node) => {
            if (node === greetingElement) {
              console.error(
                "❌ ERROR: The greeting element was REMOVED from the DOM!",
              );
              observer.disconnect();
            }
          });
        } else if (
          mutation.type === "characterData" &&
          greetingElement.textContent === ""
        ) {
          console.error(
            "❌ ERROR: The greeting content was CLEARED by an external script!",
          );
          observer.disconnect();
        }
      }
    });

    observer.observe(greetingElement, {
      childList: true,
      subtree: true,
      characterData: true,
      attributes: true,
    });

    greetingElement.textContent = greeting;
  }
}

if (typeof window !== 'undefined') {
  window.getLocalTimeBasedGreeting = getLocalTimeBasedGreeting;
}

if (typeof document !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('time-based-greeting')) {
      getLocalTimeBasedGreeting();
    }
  });
}
