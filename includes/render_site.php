<?php
declare(strict_types=1);

/**
 * Single-source RightFit page renderer.
 *
 * All four static HTML pages are the canonical design/content templates.
 * GitHub Pages serves those HTML pages directly. On PHP hosting these
 * four wrappers render the *same* files and add server-side functionality:
 * PHP navigation, canonical URLs, CSRF, form identity and flash feedback.
 *
 * No duplicate header/footer/page markup exists in PHP.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/auth.php';

function rfRenderPage(string $page): void
{
    if (!in_array($page, ['index', 'about', 'services', 'contact'], true)) {
        http_response_code(404);
        echo 'Page not found';
        return;
    }

    $source = dirname(__DIR__) . '/' . $page . '.html';
    if (!is_readable($source)) {
        error_log('RightFit shared HTML template not readable: ' . $page);
        http_response_code(503);
        echo 'Website temporarily unavailable';
        return;
    }

    $html = file_get_contents($source);
    if ($html === false) {
        http_response_code(503);
        echo 'Website temporarily unavailable';
        return;
    }

    // Keep navigation in the PHP runtime. Preserve query strings and anchors.
    $html = preg_replace_callback(
        '/\bhref="(index|about|services|contact)\.html(?=[?#"])/',
        static fn(array $match): string => 'href="' . $match[1] . '.php',
        $html
    );

    // The site preview and PHP host share content, not a hardcoded canonical host.
    $previewBase = 'https://anjal86.github.io/RightFit';
    $hostBase = rtrim(APP_URL, '/');
    $html = str_replace($previewBase, $hostBase, $html);
    $canonical = $hostBase . '/' . ($page === 'index' ? '' : $page . '.php');
    $html = preg_replace_callback(
        '/(<link rel="canonical" href=")[^"]+(")/',
        static fn(array $match): string => $match[1] . htmlspecialchars($canonical, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . $match[2],
        $html,
        1
    );
    $html = preg_replace_callback(
        '/(<meta property="og:url" content=")[^"]+(")/',
        static fn(array $match): string => $match[1] . htmlspecialchars($canonical, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . $match[2],
        $html,
        1
    );

    // All unapproved copy stays excluded from search results in both modes.
    // Explicitly enable indexing *only* after client approval and domain setup.
    if (getenv('RIGHTFIT_SEO_APPROVED') === '1') {
        $html = str_replace(
            '<meta name="robots" content="noindex, follow">',
            '<meta name="robots" content="index, follow">',
            $html
        );
    }

    if ($page === 'contact') {
        $html = rfPrepareContactForms($html);

        // Preserve the original PHP workflow's safe, escaped flash feedback.
        $flash = getFlash();
        if (is_array($flash)) {
            $statusClass = ($flash['type'] ?? '') === 'success' ? 'success' : 'error';
            $message = htmlspecialchars((string)($flash['message'] ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $notice = '<div class="container rf-php-flash" role="status">'
                . '<div class="form-feedback ' . $statusClass . '" style="display:block">'
                . $message
                . '</div></div>';
            $html = str_replace('<main id="main">', '<main id="main">' . $notice, $html);
        }
    }

    header('Content-Type: text/html; charset=utf-8');
    // Do not cache personalized CSRF fields or session-specific flash messages.
    header('Cache-Control: private, no-store');
    echo $html;
}

/**
 * Merge the PHP version's working form enhancements into the shared HTML UI.
 * The forms remain visually identical and use exactly the existing backend schema.
 */
function rfPrepareContactForms(string $html): string
{
    $placeholder = 'action="https://formspree.io/f/placeholder"';
    if (substr_count($html, $placeholder) !== 2) {
        throw new RuntimeException('Expected the two shared enquiry/referral forms');
    }
    $html = str_replace(
        $placeholder,
        'action="submit.php" data-server-intake="true"',
        $html
    );

    $csrf = htmlspecialchars(csrfToken(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    foreach (['enquiry-form' => 'enquiry', 'referral-form' => 'referral'] as $id => $kind) {
        $pattern = '/(<form\b[^>]*\bid="' . preg_quote($id, '/') . '"[^>]*>)/';
        $count = 0;
        $html = preg_replace_callback(
            $pattern,
            static function (array $match) use ($csrf, $kind): string {
                return $match[1]
                    . "\n              <!-- Server-side CSRF & submission identity (PHP only) -->"
                    . "\n              " . '<input type="hidden" name="csrf_token" value="' . $csrf . '">'
                    . "\n              " . '<input type="hidden" name="form_type" value="' . $kind . '">';
            },
            $html,
            1,
            $count
        );
        if ($count !== 1) {
            throw new RuntimeException('Missing shared form: ' . $id);
        }
    }

    // PHP interprets the [] suffix as an array; the backend expects all selections.
    $html = str_replace('name="services_requested"', 'name="services_requested[]"', $html);
    return $html;
}
