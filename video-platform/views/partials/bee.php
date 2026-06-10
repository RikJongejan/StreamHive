<?php
// bee.php - Geanimeerde bij voor lege toestanden
// Toont de SVG-animatie van de bij op pagina's zonder inhoud:
// - $beeWrap: optionele CSS-klasse voor de wrapper
// - Genereert een uniek clipId zodat meerdere instanties naast elkaar werken
$beeWrap = $beeWrap ?? null;
// Uniek ID per instantie zodat meerdere bijen op dezelfde pagina niet elkaars clip-path overschrijven
$clipId = 'bee' . uniqid();
?>
<?php if ($beeWrap !== null): ?><div class="<?= htmlspecialchars($beeWrap) ?>"><?php endif; ?>
<svg class="bee" viewBox="0 0 96 64" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <clipPath id="<?= $clipId ?>"><ellipse cx="50" cy="38" rx="26" ry="17"/></clipPath>
    </defs>
    <!-- voelsprieten -->
    <path d="M74 26 q8 -14 16 -16" stroke="#1a1a17" stroke-width="3" fill="none" stroke-linecap="round"/>
    <circle cx="91" cy="9" r="3.5" fill="#1a1a17"/>
    <!-- vleugels -->
    <ellipse class="wing wing-l" cx="40" cy="15" rx="16" ry="10"/>
    <ellipse class="wing wing-r" cx="58" cy="15" rx="16" ry="10"/>
    <!-- lijf -->
    <ellipse cx="50" cy="38" rx="26" ry="17" fill="#ffc107"/>
    <!-- strepen, netjes binnen het lijf geknipt -->
    <g clip-path="url(#<?= $clipId ?>)" fill="#1a1a17">
        <rect x="38" y="18" width="8" height="42"/>
        <rect x="52" y="18" width="8" height="42"/>
        <rect x="66" y="18" width="8" height="42"/>
    </g>
    <!-- kop -->
    <circle cx="78" cy="36" r="11" fill="#1a1a17"/>
    <circle cx="81" cy="33" r="2.6" fill="#fff"/>
    <!-- angel -->
    <path d="M24 38 l-11 -4 v8 z" fill="#1a1a17"/>
</svg>
<?php if ($beeWrap !== null): ?></div><?php endif; ?>
