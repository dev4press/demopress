import "./admin.scss";

declare const ajaxurl: string;
declare const demopress_data: { nonce: string };

const pollingElements: {
    loader: HTMLElement | null;
    content: HTMLElement | null;
} = { loader: null, content: null };

async function checkGeneratorStatus(): Promise<void> {
    const { loader, content } = pollingElements;
    const log = content?.querySelector("pre");

    if (loader === null || !log) {
        return;
    }

    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 1_000_000);
    loader.style.visibility = "visible";

    try {
        const url = new URL(ajaxurl, window.location.href);
        url.searchParams.set("action", "demopress_get_generator_status");

        const response = await fetch(url, {
            method: "POST",
            body: new URLSearchParams({ nonce: demopress_data.nonce }),
            signal: controller.signal,
        });

        if (response.ok) {
            const result: { success?: boolean; data?: unknown } | null =
                await response.json();

            if (result?.success === true && typeof result.data === "string") {
                log.textContent = result.data;
                log.scrollTop = log.scrollHeight;
            }
        }
    } catch {
        // Keep the last log visible and retry on the next poll.
    } finally {
        window.clearTimeout(timeout);
        loader.style.visibility = "hidden";
        window.setTimeout(() => void checkGeneratorStatus(), 5_000);
    }
}

function initGeneratorStatus(): void {
    pollingElements.loader = document.querySelector<HTMLElement>(
        ".demopress-generator-panel .demopress-gen-loader",
    );
    pollingElements.content = document.querySelector<HTMLElement>(
        ".demopress-generator-panel .demopress-gen-status",
    );

    void checkGeneratorStatus();
}

function updateTaskControls(event: Event): void {
    const { target } = event;

    if (target instanceof HTMLSelectElement) {
        const switchName = target.closest<HTMLElement>(
            ".demopress-builder-switch",
        )?.dataset.switch;

        if (switchName) {
            const selector = "." + CSS.escape(switchName + "-switch");
            const selected = switchName + "-data-" + target.value;
            document.querySelectorAll(selector).forEach((field) => {
                field.classList.toggle(
                    "demopress-is-hidden",
                    !field.classList.contains(selected),
                );
            });
        }

        if (target.closest(".demopress-builder-status")) {
            target
                .closest(".d4p-settings-section")
                ?.classList.toggle(
                    "demopress-type-section-hidden",
                    target.value !== "on",
                );
        }
    } else if (
        target instanceof HTMLInputElement &&
        target.matches(".demopress-type-settings-ctrl input[type=checkbox]")
    ) {
        target
            .closest(".d4p-group")
            ?.classList.toggle(
                "demopress-type-settings-hidden",
                !target.checked,
            );
    }
}

function registerTaskControls(): void {
    document.addEventListener("change", updateTaskControls);
}

const generator = {
    tmp: pollingElements,
    log: { init: initGeneratorStatus, run: checkGeneratorStatus },
    task: { run: registerTaskControls },
};

declare global {
    interface Window {
        wp?: {
            dev4press?: {
                admin: { panels: { settings: { run(): void } } };
            };
            demopress?: { generator?: typeof generator };
        };
    }
}

const wordpress = (window.wp ??= {});
const demopress = (wordpress.demopress ??= {});
demopress.generator = generator;

function initAdmin(): void {
    generator.task.run();

    if (document.getElementById("demopress-form-generator")) {
        wordpress.dev4press?.admin.panels.settings.run();
    }

    if (
        document.querySelectorAll(".demopress-gen-status-running").length === 1
    ) {
        generator.log.init();
    }
}

if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initAdmin, { once: true });
} else {
    initAdmin();
}
