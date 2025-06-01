// vite.config.js
import { defineConfig } from "file:///C:/Users/Riyad%20Hasan/Desktop/public_html/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/Riyad%20Hasan/Desktop/public_html/node_modules/laravel-vite-plugin/dist/index.mjs";
import vue from "file:///C:/Users/Riyad%20Hasan/Desktop/public_html/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
      "@": "/resources/js"
    },
    extensions: [".js", ".vue", ".json"]
  },
  define: {
    // Define global variables that will be available in the browser
    "import.meta.env.VITE_APP_URL": JSON.stringify("http://localhost:8888"),
    "import.meta.env.VITE_API_KEY": JSON.stringify(process.env.MIX_API_KEY || ""),
    "import.meta.env.VITE_GOOGLE_MAP_KEY": JSON.stringify(process.env.MIX_GOOGLE_MAP_KEY || ""),
    "import.meta.env.VITE_DEMO": JSON.stringify(process.env.MIX_DEMO || false)
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxSaXlhZCBIYXNhblxcXFxEZXNrdG9wXFxcXHB1YmxpY19odG1sXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxSaXlhZCBIYXNhblxcXFxEZXNrdG9wXFxcXHB1YmxpY19odG1sXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9Vc2Vycy9SaXlhZCUyMEhhc2FuL0Rlc2t0b3AvcHVibGljX2h0bWwvdml0ZS5jb25maWcuanNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcgfSBmcm9tICd2aXRlJztcclxuaW1wb3J0IGxhcmF2ZWwgZnJvbSAnbGFyYXZlbC12aXRlLXBsdWdpbic7XHJcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcclxuXHJcbmV4cG9ydCBkZWZhdWx0IGRlZmluZUNvbmZpZyh7XHJcbiAgICBwbHVnaW5zOiBbXHJcbiAgICAgICAgbGFyYXZlbCh7XHJcbiAgICAgICAgICAgIGlucHV0OiBbJ3Jlc291cmNlcy9jc3MvYXBwLmNzcycsICdyZXNvdXJjZXMvanMvYXBwLmpzJ10sXHJcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgdnVlKHtcclxuICAgICAgICAgICAgdGVtcGxhdGU6IHtcclxuICAgICAgICAgICAgICAgIHRyYW5zZm9ybUFzc2V0VXJsczoge1xyXG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXHJcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcclxuICAgICAgICAgICAgICAgIH0sXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgfSksXHJcbiAgICBdLFxyXG4gICAgcmVzb2x2ZToge1xyXG4gICAgICAgIGFsaWFzOiB7XHJcbiAgICAgICAgICAgIHZ1ZTogJ3Z1ZS9kaXN0L3Z1ZS5lc20tYnVuZGxlci5qcycsXHJcbiAgICAgICAgICAgICdAJzogJy9yZXNvdXJjZXMvanMnLFxyXG4gICAgICAgIH0sXHJcbiAgICAgICAgZXh0ZW5zaW9uczogWycuanMnLCAnLnZ1ZScsICcuanNvbiddXHJcbiAgICB9LFxyXG4gICAgZGVmaW5lOiB7XHJcbiAgICAgICAgLy8gRGVmaW5lIGdsb2JhbCB2YXJpYWJsZXMgdGhhdCB3aWxsIGJlIGF2YWlsYWJsZSBpbiB0aGUgYnJvd3NlclxyXG4gICAgICAgICdpbXBvcnQubWV0YS5lbnYuVklURV9BUFBfVVJMJzogSlNPTi5zdHJpbmdpZnkoJ2h0dHA6Ly9sb2NhbGhvc3Q6ODg4OCcpLFxyXG4gICAgICAgICdpbXBvcnQubWV0YS5lbnYuVklURV9BUElfS0VZJzogSlNPTi5zdHJpbmdpZnkocHJvY2Vzcy5lbnYuTUlYX0FQSV9LRVkgfHwgJycpLFxyXG4gICAgICAgICdpbXBvcnQubWV0YS5lbnYuVklURV9HT09HTEVfTUFQX0tFWSc6IEpTT04uc3RyaW5naWZ5KHByb2Nlc3MuZW52Lk1JWF9HT09HTEVfTUFQX0tFWSB8fCAnJyksXHJcbiAgICAgICAgJ2ltcG9ydC5tZXRhLmVudi5WSVRFX0RFTU8nOiBKU09OLnN0cmluZ2lmeShwcm9jZXNzLmVudi5NSVhfREVNTyB8fCBmYWxzZSksXHJcbiAgICB9LFxyXG59KTsgIl0sCiAgIm1hcHBpbmdzIjogIjtBQUFzVCxTQUFTLG9CQUFvQjtBQUNuVixPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBRWhCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMLFFBQVE7QUFBQSxNQUNKLE9BQU8sQ0FBQyx5QkFBeUIscUJBQXFCO0FBQUEsTUFDdEQsU0FBUztBQUFBLElBQ2IsQ0FBQztBQUFBLElBQ0QsSUFBSTtBQUFBLE1BQ0EsVUFBVTtBQUFBLFFBQ04sb0JBQW9CO0FBQUEsVUFDaEIsTUFBTTtBQUFBLFVBQ04saUJBQWlCO0FBQUEsUUFDckI7QUFBQSxNQUNKO0FBQUEsSUFDSixDQUFDO0FBQUEsRUFDTDtBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsT0FBTztBQUFBLE1BQ0gsS0FBSztBQUFBLE1BQ0wsS0FBSztBQUFBLElBQ1Q7QUFBQSxJQUNBLFlBQVksQ0FBQyxPQUFPLFFBQVEsT0FBTztBQUFBLEVBQ3ZDO0FBQUEsRUFDQSxRQUFRO0FBQUE7QUFBQSxJQUVKLGdDQUFnQyxLQUFLLFVBQVUsdUJBQXVCO0FBQUEsSUFDdEUsZ0NBQWdDLEtBQUssVUFBVSxRQUFRLElBQUksZUFBZSxFQUFFO0FBQUEsSUFDNUUsdUNBQXVDLEtBQUssVUFBVSxRQUFRLElBQUksc0JBQXNCLEVBQUU7QUFBQSxJQUMxRiw2QkFBNkIsS0FBSyxVQUFVLFFBQVEsSUFBSSxZQUFZLEtBQUs7QUFBQSxFQUM3RTtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
