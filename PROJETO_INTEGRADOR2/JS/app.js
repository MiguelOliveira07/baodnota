const MENU_ANIM_MS = 220;
const PHOTO_STORAGE_KEY = "monitoria_profile_photo_data_url";
const PROFILE_STORAGE_PREFIX = "monitoria_profile_data_";

function setMenuOpenState(menu, btn, open) {
  if (open) {
    menu.hidden = false;
    requestAnimationFrame(() => {
      menu.classList.add("is-open");
    });
    btn.setAttribute("aria-expanded", "true");
    return;
  }

  menu.classList.remove("is-open");
  btn.setAttribute("aria-expanded", "false");

  const hideAfterAnimation = () => {
    if (!menu.classList.contains("is-open")) {
      menu.hidden = true;
    }
  };

  menu.addEventListener("transitionend", hideAfterAnimation, { once: true });
  window.setTimeout(hideAfterAnimation, MENU_ANIM_MS + 40);
}

function initMenu() {
  const btn = document.querySelector(".menu-toggle");
  const menu = document.querySelector("#menuDropdown");
  const currentPath = window.location.pathname.split("/").pop() || "index.html";
  const authPages = ["index.html", "cadastro.html", "recuperar_senha.html"];

  if (!btn || !menu || btn.dataset.bound === "true") {
    return;
  }

  if (authPages.includes(currentPath)) {
    btn.hidden = true;
    btn.setAttribute("aria-hidden", "true");
    menu.hidden = true;
    menu.classList.remove("is-open");
    return;
  }

  setMenuOpenState(menu, btn, false);

  btn.addEventListener("click", (event) => {
    event.stopPropagation();
    const isOpen = !menu.hidden && menu.classList.contains("is-open");
    setMenuOpenState(menu, btn, !isOpen);
  });

  document.addEventListener("click", (event) => {
    if (!menu.contains(event.target) && !btn.contains(event.target)) {
      setMenuOpenState(menu, btn, false);
    }
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape") {
      setMenuOpenState(menu, btn, false);
    }
  });

  menu.querySelectorAll("a").forEach((link) => {
    link.addEventListener("click", () => {
      setMenuOpenState(menu, btn, false);
    });
  });

  menu.querySelectorAll("a").forEach((link) => {
    if (link.getAttribute("href") === currentPath) {
      link.classList.add("is-active");
    }
  });

  btn.dataset.bound = "true";
}

function applyPhotoToProfile(profileSide, dataUrl) {
  if (!profileSide) {
    return;
  }

  const avatarImage = profileSide.querySelector(".avatar-image");
  const avatarPlaceholder = profileSide.querySelector(".avatar-placeholder");

  if (!avatarImage) {
    return;
  }

  avatarImage.src = dataUrl;
  avatarImage.hidden = false;

  if (avatarPlaceholder) {
    avatarPlaceholder.hidden = true;
  }
}

function applyPhotoToAllProfiles(dataUrl) {
  document.querySelectorAll(".profile-side").forEach((profileSide) => {
    applyPhotoToProfile(profileSide, dataUrl);
  });
}

function restoreStoredPhoto() {
  try {
    const storedPhoto = localStorage.getItem(PHOTO_STORAGE_KEY);
    if (storedPhoto) {
      applyPhotoToAllProfiles(storedPhoto);
    }
  } catch (error) {
    console.error("Falha ao ler a foto salva:", error);
  }
}

function initPhotoUpload() {
  const inputs = document.querySelectorAll(".photo-input");
  restoreStoredPhoto();

  inputs.forEach((input) => {
    if (input.dataset.bound === "true") {
      return;
    }

    input.addEventListener("change", () => {
      const file = input.files && input.files[0];
      if (!file || !file.type.startsWith("image/")) {
        return;
      }

      const reader = new FileReader();
      reader.onload = () => {
        const dataUrl = typeof reader.result === "string" ? reader.result : "";
        if (!dataUrl) {
          return;
        }

        applyPhotoToAllProfiles(dataUrl);
        try {
          localStorage.setItem(PHOTO_STORAGE_KEY, dataUrl);
        } catch (error) {
          console.error("Falha ao salvar a foto:", error);
        }
      };

      reader.readAsDataURL(file);
    });

    input.dataset.bound = "true";
  });
}

function setInputsEditable(form, editable) {
  form.querySelectorAll("input, textarea").forEach((field) => {
    field.readOnly = !editable;
  });
}

function setProfileEditMode(form, editing) {
  const editBtn = form.querySelector(".edit-profile-btn");
  const saveBtn = form.querySelector(".save-profile-btn");

  setInputsEditable(form, editing);

  if (editBtn) {
    editBtn.hidden = editing;
    editBtn.disabled = false;
    editBtn.textContent = "Alterar informacoes";
  }

  if (saveBtn) {
    saveBtn.hidden = !editing;
  }
}

function setProfileEditMode(form, editing) {
  const editBtn = form.querySelector(".edit-profile-btn");
  const saveBtn = form.querySelector(".save-profile-btn");

  setInputsEditable(form, editing);

  if (editBtn) {
    editBtn.hidden = editing;
    editBtn.disabled = false;
    editBtn.textContent = "Alterar informacoes";
  }

  if (saveBtn) {
    saveBtn.hidden = !editing;
  }
}

function fillFormFromData(form, data) {
  Object.entries(data).forEach(([name, value]) => {
    const field = form.querySelector(`[name="${name}"]`);
    if (field) {
      field.value = String(value);
    }
  });
}

function readFormData(form) {
  const data = {};
  form.querySelectorAll("input[name], textarea[name]").forEach((field) => {
    data[field.name] = field.value;
  });
  return data;
}

function initProfileEditForms() {
  const forms = document.querySelectorAll(".profile-edit-form");

  forms.forEach((form) => {
    if (form.dataset.bound === "true") {
      return;
    }

    const profileId = form.dataset.profile || "default";
    const storageKey = `${PROFILE_STORAGE_PREFIX}${profileId}`;
    const editBtn = form.querySelector(".edit-profile-btn");

    try {
      const stored = localStorage.getItem(storageKey);
      if (stored) {
        fillFormFromData(form, JSON.parse(stored));
      }
    } catch (error) {
      console.error("Falha ao carregar dados do perfil:", error);
    }

    setProfileEditMode(form, false);

    if (editBtn) {
      editBtn.addEventListener("click", () => {
        setProfileEditMode(form, true);
      });
    }

    form.addEventListener("submit", (event) => {
      event.preventDefault();

      try {
        localStorage.setItem(storageKey, JSON.stringify(readFormData(form)));
      } catch (error) {
        console.error("Falha ao salvar dados do perfil:", error);
      }

      setProfileEditMode(form, false);
    });

    form.dataset.bound = "true";
  });
}

function initHomeCarousel() {
  const carousel = document.querySelector(".home-carousel");
  const dots = document.querySelectorAll(".home-carousel-dots .dot");

  if (!carousel || dots.length === 0 || carousel.dataset.bound === "true") {
    return;
  }

  const autoplayMs = 4000;
  let autoplayId = 0;

  const updateActiveDot = () => {
    const slideWidth = carousel.clientWidth || 1;
    const index = Math.round(carousel.scrollLeft / slideWidth);
    dots.forEach((dot, dotIndex) => {
      dot.classList.toggle("is-active", dotIndex === index);
    });
  };

  const getCurrentIndex = () => {
    const slideWidth = carousel.clientWidth || 1;
    return Math.round(carousel.scrollLeft / slideWidth);
  };

  const goToIndex = (index) => {
    const left = carousel.clientWidth * index;
    carousel.scrollTo({ left, behavior: "smooth" });
  };

  const goToNext = () => {
    const current = getCurrentIndex();
    const next = (current + 1) % dots.length;
    goToIndex(next);
  };

  const stopAutoplay = () => {
    if (autoplayId) {
      window.clearInterval(autoplayId);
      autoplayId = 0;
    }
  };

  const startAutoplay = () => {
    stopAutoplay();
    autoplayId = window.setInterval(goToNext, autoplayMs);
  };

  dots.forEach((dot) => {
    dot.addEventListener("click", () => {
      const index = Number(dot.dataset.slide || 0);
      goToIndex(index);
      startAutoplay();
    });
  });

  carousel.addEventListener("pointerdown", stopAutoplay);
  carousel.addEventListener("pointerup", startAutoplay);
  carousel.addEventListener("mouseenter", stopAutoplay);
  carousel.addEventListener("mouseleave", startAutoplay);
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      stopAutoplay();
      return;
    }
    startAutoplay();
  });

  carousel.addEventListener("scroll", updateActiveDot, { passive: true });
  window.addEventListener("resize", updateActiveDot);
  updateActiveDot();
  startAutoplay();
  carousel.dataset.bound = "true";
}

document.addEventListener("components:loaded", () => {
  initMenu();
  initPhotoUpload();
  initProfileEditForms();
  initHomeCarousel();
});

document.addEventListener("DOMContentLoaded", () => {
  initMenu();
  initPhotoUpload();
  initProfileEditForms();
  initHomeCarousel();
});
