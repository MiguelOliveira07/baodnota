async function loadComponent(selector, url) {
  const el = document.querySelector(selector);
  if (!el) {
    return;
  }

  try {
    const res = await fetch(url, { cache: "no-store" });
    if (!res.ok) {
      throw new Error(`Falha ao carregar ${url} (${res.status})`);
    }

    el.innerHTML = await res.text();
  } catch (err) {
    console.error(err);
    el.innerHTML = `<div style="padding:12px;border:1px solid #d64374;border-radius:8px">Erro ao carregar componente: <b>${url}</b></div>`;
  }
}

document.addEventListener("DOMContentLoaded", async () => {
  await Promise.all([
    loadComponent("#app-header", "components/header.html"),
    loadComponent("#app-footer", "components/footer.html")
  ]);

  document.dispatchEvent(new Event("components:loaded"));
});
