Guía: Integración con Formspree + GitHub Actions

Resumen
-------
Esta integración permite que tu sitio (servido desde GitHub Pages) envíe pedidos a Formspree. Un GitHub Action leerá periódicamente (o a demanda) las sumisiones desde Formspree y las guardará en `compras/formspree_orders.json` dentro del repo.

Pasos rápidos
------------
1. Crear el formulario en Formspree
   - Regístrate en https://formspree.io y crea un formulario.
   - En el panel del formulario obtendrás el `Form ID` y una `API Key` (o token) para leer sumisiones.

2. Configurar secretos en GitHub
   - Ve a tu repositorio en GitHub → Settings → Secrets and variables → Actions → New repository secret.
   - Añade estos secrets:
     - `FORMSPREE_API_KEY` : tu token/API key de Formspree
     - `FORMSPREE_FORM_ID` : el ID del formulario (p.ej. `f/abcxyz`)
   - `GITHUB_TOKEN` ya está disponible para Actions (no es necesario crearlo manualmente).

3. Reemplazar el endpoint en el cliente
   - En `index.html` encontrarás la línea:
     ```js
     const FORMSPREE_ENDPOINT = 'https://formspree.io/f/yourFormId';
     ```
   - Cambia `'https://formspree.io/f/yourFormId'` por el endpoint público que Formspree te da para enviar formularios (p.ej. `https://formspree.io/f/abcxyz`).

4. Subir el sitio a GitHub Pages
   - Crea un repo, sube todo el contenido y activa GitHub Pages (Settings → Pages → branch `main` → / (root)).

5. Ejecutar el workflow manualmente
   - En GitHub → Actions → "Sync Formspree Submissions to Repo" → Run workflow (workflow_dispatch).
   - El Action descargará sumisiones y las guardará en `compras/formspree_orders.json`.

Archivo generado por el Action
------------------------------
- `compras/formspree_orders.json` — lista acumulada de sumisiones recibidas desde Formspree.

Notas y recomendaciones
----------------------
- Seguridad: los secrets (`FORMSPREE_API_KEY`) permanecen en GitHub y no se exponen al frontend.
- Límite: Formspree tiene un plan gratuito con límites de envíos. Revisa su plan si esperas mayor volumen.
- Si prefieres que los pedidos lleguen directamente a una dirección alternativa o se integren con Slack, usa las integraciones de Formspree (o el panel para reenvío).

¿Quieres que:
- Te ayude a crear el formulario en Formspree y te pegue el `FORMSPREE_ENDPOINT` en `index.html` automáticamente? (necesitaré que pegues aquí el endpoint que te da Formspree).
- O prefieres que te guíe paso a paso por pantalla para crear el form y configurar los secretos en GitHub?