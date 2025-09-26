import React from "react";
import { motion } from "framer-motion";

const Footer = () => {
  const socialLinks = [
    { name: "تويتر", icon: "🐦", href: "#" },
    { name: "إنستغرام", icon: "📷", href: "#" },
    { name: "فيسبوك", icon: "📘", href: "#" },
    { name: "يوتيوب", icon: "🎥", href: "#" }
  ];

  const companyLinks = [
    "من نحن", "تواصل معنا", "الوظائف", "المدونة", "الأخبار"
  ];

  const supportLinks = [
    "مركز المساعدة", "الأسئلة الشائعة", "سياسة الإرجاع", "الشحن والتوصيل", "الدعم الفني"
  ];

  const categories = [
    "الهواتف الذكية", "أجهزة الكمبيوتر", "الأجهزة اللوحية", "السماعات", "الإكسسوارات"
  ];

  return (
    <footer className="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 text-white relative overflow-hidden">
      {/* Background Decorations */}
      <div className="absolute inset-0 opacity-50" style={{backgroundImage: "url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.02\"%3E%3Ccircle cx=\"30\" cy=\"30\" r=\"1\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')"}}></div>
      <div className="absolute top-0 left-0 w-64 h-64 bg-gradient-to-r from-cyan-400/10 to-blue-500/10 rounded-full blur-3xl"></div>
      <div className="absolute bottom-0 right-0 w-64 h-64 bg-gradient-to-r from-purple-400/10 to-pink-500/10 rounded-full blur-3xl"></div>

      <div className="relative z-10">
        {/* Newsletter Section */}
        <div className="border-b border-white/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.8 }}
              viewport={{ once: true }}
              className="text-center"
            >
              <h3 className="text-3xl font-bold mb-4">
                ابق على اطلاع دائم
                <span className="bg-gradient-to-r from-cyan-400 to-purple-600 bg-clip-text text-transparent">
                  {" "}بأحدث العروض{" "}
                </span>
              </h3>
              <p className="text-gray-300 text-lg mb-8 max-w-2xl mx-auto">
                اشترك في نشرتنا الإخبارية واحصل على أحدث العروض والمنتجات الجديدة
              </p>

              <motion.div
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.2 }}
                viewport={{ once: true }}
                className="flex flex-col sm:flex-row gap-4 max-w-md mx-auto"
              >
                <input
                  type="email"
                  placeholder="أدخل بريدك الإلكتروني"
                  className="flex-1 px-6 py-3 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-cyan-500"
                />
                <motion.button
                  whileHover={{ scale: 1.05 }}
                  whileTap={{ scale: 0.95 }}
                  className="px-8 py-3 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-bold rounded-full shadow-lg hover:shadow-cyan-500/25 transition-all duration-300"
                >
                  اشتراك
                </motion.button>
              </motion.div>
            </motion.div>
          </div>
        </div>

        {/* Main Footer Content */}
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            {/* Brand Section */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6 }}
              viewport={{ once: true }}
              className="space-y-6"
            >
              <div className="flex items-center space-x-3">
                <div className="text-3xl">🚀</div>
                <h2 className="text-2xl font-bold bg-gradient-to-r from-cyan-400 to-purple-600 bg-clip-text text-transparent">
                  متجر تقني
                </h2>
              </div>

              <p className="text-gray-300 leading-relaxed">
                وجهتك الأولى لأحدث الأجهزة التقنية والذكية بأفضل الأسعار وأعلى جودة في المنطقة
              </p>

              <div className="flex space-x-4">
                {socialLinks.map((social, index) => (
                  <motion.a
                    key={social.name}
                    href={social.href}
                    initial={{ opacity: 0, scale: 0 }}
                    whileInView={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.3, delay: index * 0.1 }}
                    viewport={{ once: true }}
                    whileHover={{ scale: 1.2, y: -2 }}
                    className="w-12 h-12 bg-gradient-to-r from-cyan-500/20 to-purple-600/20 backdrop-blur-sm border border-white/10 rounded-full flex items-center justify-center text-xl hover:border-white/30 transition-all duration-300"
                  >
                    {social.icon}
                  </motion.a>
                ))}
              </div>
            </motion.div>

            {/* Company Links */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.1 }}
              viewport={{ once: true }}
              className="space-y-6"
            >
              <h3 className="text-xl font-bold text-white">الشركة</h3>
              <ul className="space-y-3">
                {companyLinks.map((link, index) => (
                  <motion.li
                    key={index}
                    whileHover={{ x: 5 }}
                    className="text-gray-300 hover:text-cyan-400 cursor-pointer transition-colors duration-200"
                  >
                    {link}
                  </motion.li>
                ))}
              </ul>
            </motion.div>

            {/* Support Links */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.2 }}
              viewport={{ once: true }}
              className="space-y-6"
            >
              <h3 className="text-xl font-bold text-white">الدعم</h3>
              <ul className="space-y-3">
                {supportLinks.map((link, index) => (
                  <motion.li
                    key={index}
                    whileHover={{ x: 5 }}
                    className="text-gray-300 hover:text-cyan-400 cursor-pointer transition-colors duration-200"
                  >
                    {link}
                  </motion.li>
                ))}
              </ul>
            </motion.div>

            {/* Categories */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.3 }}
              viewport={{ once: true }}
              className="space-y-6"
            >
              <h3 className="text-xl font-bold text-white">الفئات</h3>
              <ul className="space-y-3">
                {categories.map((category, index) => (
                  <motion.li
                    key={index}
                    whileHover={{ x: 5 }}
                    className="text-gray-300 hover:text-cyan-400 cursor-pointer transition-colors duration-200"
                  >
                    {category}
                  </motion.li>
                ))}
              </ul>
            </motion.div>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="border-t border-white/10">
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <motion.div
              initial={{ opacity: 0 }}
              whileInView={{ opacity: 1 }}
              transition={{ duration: 0.6 }}
              viewport={{ once: true }}
              className="flex flex-col md:flex-row justify-between items-center gap-4"
            >
              <div className="text-gray-300 text-sm">
                © 2025 متجر تقني. جميع الحقوق محفوظة.
              </div>

              <div className="flex gap-6 text-sm text-gray-300">
                <a href="#" className="hover:text-cyan-400 transition-colors duration-200">
                  سياسة الخصوصية
                </a>
                <a href="#" className="hover:text-cyan-400 transition-colors duration-200">
                  شروط الاستخدام
                </a>
                <a href="#" className="hover:text-cyan-400 transition-colors duration-200">
                  ملفات تعريف الارتباط
                </a>
              </div>
            </motion.div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
