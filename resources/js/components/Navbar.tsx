import { useState, useEffect } from "react";
import { motion, AnimatePresence } from "framer-motion";

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  const menuItems = [
    { name: "الرئيسية", href: "#home", icon: "🏠" },
    { name: "المتجر", href: "#store", icon: "🛒" },
    { name: "المدونة", href: "#blog", icon: "📝" },
    { name: "السلة", href: "#cart", icon: "🛍️" },
    { name: "تسجيل الدخول", href: "#login", icon: "👤" }
  ];

  useEffect(() => {
    const handleScroll = () => {
      setScrolled(window.scrollY > 50);
    };
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  return (
    <motion.nav
      initial={{ y: -100 }}
      animate={{ y: 0 }}
      transition={{ duration: 0.6 }}
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        scrolled
          ? 'bg-white/10 backdrop-blur-xl border-b border-white/10 shadow-2xl'
          : 'bg-transparent'
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <motion.div
            initial={{ opacity: 0, x: -20 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.6, delay: 0.1 }}
            className="flex items-center space-x-4"
          >
            <div className="text-2xl">🚀</div>
            <h1 className="text-xl font-bold bg-gradient-to-r from-cyan-400 to-purple-600 bg-clip-text text-transparent">
              متجر تقني
            </h1>
          </motion.div>

          {/* Desktop Menu */}
          <div className="hidden md:flex items-center space-x-8">
            {menuItems.map((item, index) => (
              <motion.a
                key={item.name}
                href={item.href}
                initial={{ opacity: 0, y: -20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.1 * index }}
                className={`relative px-3 py-2 text-white hover:text-cyan-400 transition-colors duration-200 group ${
                  item.name === "الرئيسية" ? "text-cyan-400" : ""
                }`}
              >
                <span className="flex items-center space-x-2">
                  <span className="text-sm">{item.icon}</span>
                  <span>{item.name}</span>
                </span>

                {/* Active indicator */}
                {item.name === "الرئيسية" && (
                  <motion.div
                    layoutId="activeTab"
                    className="absolute inset-0 bg-gradient-to-r from-cyan-400/20 to-purple-600/20 rounded-lg"
                  />
                )}

                {/* Hover effect */}
                <div className="absolute inset-0 bg-gradient-to-r from-cyan-400/10 to-purple-600/10 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200" />
              </motion.a>
            ))}

            {/* CTA Button */}
            <motion.button
              initial={{ opacity: 0, scale: 0.8 }}
              animate={{ opacity: 1, scale: 1 }}
              transition={{ duration: 0.6, delay: 0.5 }}
              whileHover={{ scale: 1.05 }}
              whileTap={{ scale: 0.95 }}
              className="px-6 py-2 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-medium rounded-full shadow-lg hover:shadow-cyan-500/25 transition-all duration-300"
            >
              احصل على خصم
            </motion.button>
          </div>

          {/* Mobile Hamburger Button */}
          <motion.button
            initial={{ opacity: 0, scale: 0.8 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.6, delay: 0.3 }}
            className="md:hidden relative z-10 p-2 text-white focus:outline-none"
            onClick={() => setIsOpen(true)}
            aria-label="Open menu"
          >
            <div className="w-6 h-5 relative">
              <span className="absolute w-full h-0.5 bg-white top-0 transition-all duration-300" />
              <span className="absolute w-full h-0.5 bg-white top-2 transition-all duration-300" />
              <span className="absolute w-full h-0.5 bg-white top-4 transition-all duration-300" />
            </div>
          </motion.button>
        </div>
      </div>

      {/* Mobile Menu Overlay */}
      <AnimatePresence>
        {isOpen && (
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 bg-gradient-to-br from-slate-900/95 via-purple-900/95 to-slate-900/95 backdrop-blur-xl z-40"
          >
            <div className="flex flex-col h-full">
              {/* Header */}
              <div className="flex items-center justify-between p-6">
                <div className="flex items-center space-x-4">
                  <div className="text-2xl">🚀</div>
                  <h1 className="text-xl font-bold bg-gradient-to-r from-cyan-400 to-purple-600 bg-clip-text text-transparent">
                    متجر تقني
                  </h1>
                </div>

                <motion.button
                  initial={{ rotate: 0 }}
                  animate={{ rotate: 180 }}
                  exit={{ rotate: 0 }}
                  onClick={() => setIsOpen(false)}
                  className="p-2 text-white hover:text-cyan-400 transition-colors"
                  aria-label="Close menu"
                >
                  <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </motion.button>
              </div>

              {/* Menu Items */}
              <div className="flex-1 flex flex-col justify-center px-6">
                <div className="space-y-6">
                  {menuItems.map((item, index) => (
                    <motion.a
                      key={item.name}
                      href={item.href}
                      initial={{ opacity: 0, x: -30 }}
                      animate={{ opacity: 1, x: 0 }}
                      transition={{ duration: 0.3, delay: index * 0.1 }}
                      className={`flex items-center space-x-4 p-4 rounded-xl transition-all duration-200 ${
                        item.name === "الرئيسية"
                          ? "bg-gradient-to-r from-cyan-400/20 to-purple-600/20 text-cyan-400"
                          : "text-white hover:bg-white/10 hover:text-cyan-400"
                      }`}
                      onClick={() => setIsOpen(false)}
                    >
                      <span className="text-2xl">{item.icon}</span>
                      <span className="text-xl font-medium">{item.name}</span>
                    </motion.a>
                  ))}
                </div>

                {/* Mobile CTA */}
                <motion.button
                  initial={{ opacity: 0, y: 30 }}
                  animate={{ opacity: 1, y: 0 }}
                  transition={{ duration: 0.5, delay: 0.5 }}
                  className="mt-8 w-full px-6 py-4 bg-gradient-to-r from-cyan-500 to-purple-600 text-white font-bold rounded-2xl shadow-2xl"
                >
                  احصل على خصم خاص
                </motion.button>
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>
    </motion.nav>
  );
};

export default Navbar;
