import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#ff6b35", // สีส้มหลัก
                secondary: "#f7931e", // สีส้มอ่อน
                accent: "#5cb85c", // สีเขียว
                neutral: "#ccc", // สีตัวอักษร/พื้นหลังอ่อน
                dark: "#1a1a1a", // สีพื้นหลังเข้ม
                dark2: "#2d1810", // สี gradient background
            },
            backgroundImage: {
                "hero-gradient":
                    "linear-gradient(135deg, #1a1a1a 0%, #2d1810 100%)",
                "text-gradient":
                    "linear-gradient(135deg, #fff 0%, #ff6b35 100%)",
                "header-gradient":
                    "linear-gradient(135deg, #181818ff 0%, #ff6b3580 100%)",
                "btn-gradient":
                    "linear-gradient(135deg, #ff6b35 0%, #f7931e 100%)",
                "btn-gradient-accent":
                    "linear-gradient(135deg, #3bb189ff 50%, #22c55e 100%)",
            },
        },
    },

    plugins: [forms, typography],
};
