<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>Feedback</title>

    <style>
        :root {
            --bg: #0b1220;
            --card: #0f1a33;
            --text: #e7eefc;
            --muted: rgba(231, 238, 252, .75);
            --border: rgba(255,255,255,.10);
            --danger: #ff4d4f;
            --ok: #20c997;
            --btn: #3b82f6;
            --btnHover: #2563eb;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue";
            background: transparent;
            color: var(--text);
        }

        .wrap { padding: 14px; }
        .card {
            background: linear-gradient(180deg, rgba(15,26,51,.95), rgba(15,26,51,.85));
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px;
            box-shadow: 0 12px 30px rgba(0,0,0,.25);
            max-width: 520px;
            margin: 0 auto;
        }

        h1 {
            font-size: 18px;
            margin: 0 0 10px;
            letter-spacing: .2px;
        }
        p.sub {
            margin: 0 0 14px;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.35;
        }

        label { display:block; font-size: 12px; color: var(--muted); margin: 10px 0 6px; }
        input, textarea {
            width: 100%;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.04);
            color: var(--text);
            border-radius: 12px;
            padding: 10px 12px;
            outline: none;
        }
        textarea { min-height: 110px; resize: vertical; }

        .row { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
        @media (max-width: 520px) { .row { grid-template-columns: 1fr; } }

        .help {
            margin-top: 6px;
            font-size: 12px;
            color: var(--muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 14px;
            padding: 11px 14px;
            border: 0;
            border-radius: 12px;
            background: var(--btn);
            color: white;
            font-weight: 600;
            cursor: pointer;
        }
        .btn:hover { background: var(--btnHover); }
        .btn:disabled { opacity: .65; cursor: not-allowed; }

        .msg {
            display: none;
            margin-top: 12px;
            padding: 10px 12px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font-size: 13px;
            line-height: 1.35;
        }
        .msg.ok { display:block; border-color: rgba(32,201,151,.35); background: rgba(32,201,151,.10); }
        .msg.err { display:block; border-color: rgba(255,77,79,.35); background: rgba(255,77,79,.08); }

        .errors {
            margin: 10px 0 0;
            padding-left: 18px;
            color: rgba(255,255,255,.92);
        }
        .errors li { margin: 4px 0; }

        .foot {
            margin-top: 12px;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="card">
        <h1>Связаться с нами</h1>
        <p class="sub">Заполните форму — менеджер ответит вам как можно скорее. Можно прикрепить файлы.</p>

        <form id="ticketForm" enctype="multipart/form-data" novalidate>
            <div class="row">
                <div>
                    <label for="customer_name">Name</label>
                    <input id="customer_name" name="customer_name" type="text" placeholder="Customer name" required>
                </div>
                <div>
                    <label for="customer_phone">Phone number</label>
                    <input id="customer_phone" name="customer_phone" type="tel" placeholder="+380XXXXXXXXX" required>
                </div>
            </div>

            <div class="row">
                <div>
                    <label for="customer_email">Email</label>
                    <input id="customer_email" name="customer_email" type="email" placeholder="Email">
                </div>
                <div>
                    <label for="subject">Subject</label>
                    <input id="subject" name="subject" type="text" placeholder="Subject" required>
                </div>
            </div>

            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Message text..." required></textarea>

            <label for="files">Files</label>
            <input id="files" name="files[]" type="file" multiple>

            <button class="btn" type="submit" id="submitBtn">
                <span id="btnText">Submit</span>
                <span id="spinner" style="display:none;">⏳</span>
            </button>

            <div id="successBox" class="msg ok"></div>
            <div id="errorBox" class="msg err"></div>
        </form>

        <div class="foot">Iframe Widget</div>
    </div>
</div>

<script>
    (function () {
        const form = document.getElementById('ticketForm');
        const btn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const spinner = document.getElementById('spinner');
        const successBox = document.getElementById('successBox');
        const errorBox = document.getElementById('errorBox');

        function setLoading(isLoading) {
            btn.disabled = isLoading;
            spinner.style.display = isLoading ? 'inline' : 'none';
            btnText.textContent = isLoading ? 'Отправка...' : 'Отправить';
        }

        function showSuccess(message) {
            successBox.textContent = message;
            errorBox.className = 'msg err';
            errorBox.style.display = 'none';
            successBox.className = 'msg ok';
            successBox.style.display = 'block';
        }

        function showErrors(title, errors) {
            let html = `<div style="font-weight:600;margin-bottom:6px;">${title}</div>`;
            if (errors && errors.length) {
                html += `<ul class="errors">${errors.map(e => `<li>${escapeHtml(e)}</li>`).join('')}</ul>`;
            }
            errorBox.innerHTML = html;

            successBox.className = 'msg ok';
            successBox.style.display = 'none';
            errorBox.className = 'msg err';
            errorBox.style.display = 'block';
        }

        function escapeHtml(str) {
            return String(str)
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            successBox.style.display = 'none';
            errorBox.style.display = 'none';

            const formData = new FormData(form);

            setLoading(true);
            try {
                const res = await fetch('/api/tickets', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok) {
                    showSuccess('Заявка отправлена! Спасибо. Мы свяжемся с вами.');
                    form.reset();
                    return;
                }

                // Laravel validation 422
                if (res.status === 422 && data && data.errors) {
                    const list = [];
                    Object.keys(data.errors).forEach((k) => {
                        (data.errors[k] || []).forEach((msg) => list.push(msg));
                    });
                    showErrors('Проверьте форму:', list);
                    return;
                }

                // Other errors
                showErrors('Не удалось отправить заявку.', [
                    data.message ? data.message : 'Попробуйте ещё раз чуть позже.'
                ]);
            } catch (err) {
                showErrors('Ошибка сети.', ['Не удалось подключиться к серверу.']);
            } finally {
                setLoading(false);
            }
        });
    })();
</script>
</body>
</html>
