<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* core/themes/stable/templates/admin/locale-translation-update-info.html.twig */
class __TwigTemplate_3534b37d137023ed594d76898cd7a7cfd0fdb9648688fd53ee6efc3ccc45cdb6 extends \Twig\Template
{
    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
        $this->sandbox = $this->env->getExtension('\Twig\Extension\SandboxExtension');
        $tags = ["if" => 18, "set" => 19, "trans" => 20, "for" => 34];
        $filters = ["safe_join" => 19, "escape" => 20, "length" => 25, "format_date" => 35, "t" => 45, "default" => 50];
        $functions = [];

        try {
            $this->sandbox->checkSecurity(
                ['if', 'set', 'trans', 'for'],
                ['safe_join', 'escape', 'length', 'format_date', 't', 'default'],
                []
            );
        } catch (SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 16
        echo "<div class=\"locale-translation-update__wrapper\" tabindex=\"0\" role=\"button\">
  <span class=\"locale-translation-update__prefix visually-hidden\">Show description</span>
  ";
        // line 18
        if (($context["modules"] ?? null)) {
            // line 19
            echo "    ";
            $context["module_list"] = $this->env->getExtension('Drupal\Core\Template\TwigExtension')->safeJoin($this->env, $this->sandbox->ensureToStringAllowed(($context["modules"] ?? null)), ", ");
            // line 20
            echo "    <span class=\"locale-translation-update__message\">";
            echo t("Updates for: @module_list", array("@module_list" => ($context["module_list"] ?? null), ));
            echo "</span>
  ";
        } elseif (        // line 21
($context["not_found"] ?? null)) {
            // line 22
            echo "    <span class=\"locale-translation-update__message\">";
            // line 23
            echo \Drupal::translation()->formatPlural(abs(twig_length_filter($this->env,             // line 25
($context["not_found"] ?? null))), "Missing translations for one project", "Missing translations for @count projects", array());
            // line 28
            echo "</span>
  ";
        }
        // line 30
        echo "  ";
        if ((($context["updates"] ?? null) || ($context["not_found"] ?? null))) {
            // line 31
            echo "    <div class=\"locale-translation-update__details\">
      ";
            // line 32
            if (($context["updates"] ?? null)) {
                // line 33
                echo "        <ul>
          ";
                // line 34
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(($context["updates"] ?? null));
                foreach ($context['_seq'] as $context["_key"] => $context["update"]) {
                    // line 35
                    echo "            <li>";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["update"], "name", [])), "html", null, true);
                    echo " (";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, call_user_func_array($this->env->getFilter('format_date')->getCallable(), [$this->sandbox->ensureToStringAllowed($this->getAttribute($context["update"], "timestamp", [])), "html_date"]), "html", null, true);
                    echo ")</li>
          ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['update'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 37
                echo "        </ul>
      ";
            }
            // line 39
            echo "      ";
            if (($context["not_found"] ?? null)) {
                // line 40
                echo "        ";
                // line 44
                echo "        ";
                if (($context["updates"] ?? null)) {
                    // line 45
                    echo "          ";
                    echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Missing translations for:"));
                    echo "
        ";
                }
                // line 47
                echo "        ";
                if (($context["not_found"] ?? null)) {
                    // line 48
                    echo "          <ul>
            ";
                    // line 49
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(($context["not_found"] ?? null));
                    foreach ($context['_seq'] as $context["_key"] => $context["update"]) {
                        // line 50
                        echo "              <li>";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["update"], "name", [])), "html", null, true);
                        echo " (";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, (($this->getAttribute($context["update"], "version", [], "any", true, true)) ? (_twig_default_filter($this->sandbox->ensureToStringAllowed($this->getAttribute($context["update"], "version", [])), t("no version"))) : (t("no version"))), "html", null, true);
                        echo "). ";
                        echo $this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->sandbox->ensureToStringAllowed($this->getAttribute($context["update"], "info", [])), "html", null, true);
                        echo "</li>
            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['update'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 52
                    echo "          </ul>
        ";
                }
                // line 54
                echo "      ";
            }
            // line 55
            echo "    </div>
  ";
        }
        // line 57
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "core/themes/stable/templates/admin/locale-translation-update-info.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  158 => 57,  154 => 55,  151 => 54,  147 => 52,  134 => 50,  130 => 49,  127 => 48,  124 => 47,  118 => 45,  115 => 44,  113 => 40,  110 => 39,  106 => 37,  95 => 35,  91 => 34,  88 => 33,  86 => 32,  83 => 31,  80 => 30,  76 => 28,  74 => 25,  73 => 23,  71 => 22,  69 => 21,  64 => 20,  61 => 19,  59 => 18,  55 => 16,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Source("", "core/themes/stable/templates/admin/locale-translation-update-info.html.twig", "/var/www/html/drupal8/core/themes/stable/templates/admin/locale-translation-update-info.html.twig");
    }
}
