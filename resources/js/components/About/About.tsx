import { motion } from "framer-motion";
import { AboutWaves } from "../SVG";
import { useState } from "react";


const About = () => {
  const [currentIndex, setCurrentIndex] = useState(0);

  const next = () =>
    setCurrentIndex((prev) => (prev + 1) % features.length);
  const prev = () =>
    setCurrentIndex((prev) =>
      prev === 0 ? features.length - 1 : prev - 1
    );

  return (
    <section className="relative rounded-3xl flex items-center justify-center py-7 sm:py-20 px-4 sm:px-8 lg:px-16 h-full overflow-hidden">
      <AboutWaves className="sm:rounded-3xl hidden sm:inline absolute -z-0 inset-0 h-full" />
      <div className="relative w-full rounded-3xl p-8 sm:p-12 bg-transparent z-10">
        <div className="relative z-10 text-center space-y-10">
          {/* Heading */}
          <div>
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-bold text-white">
              WHY US?
            </h2>
            <p className="text-gray-400 text-sm sm:text-base mt-2">
              Our values and philosophy
            </p>
          </div>

          {/* Features */}
          {/* Mobile: Carousel */}
          <div className="sm:hidden relative">
            <motion.div
              key={currentIndex}
              initial={{ x: 50, opacity: 0 }}
              animate={{ x: 0, opacity: 1 }}
              exit={{ x: -50, opacity: 0 }}
              transition={{ duration: 0.3 }}
            >
              <Feature {...features[currentIndex]} />
            </motion.div>

            {/* Carousel Controls */}
            <div className="flex justify-between items-center mt-4">
              <button
                onClick={prev}
                className="p-2 text-white hover:scale-110 transition-transform"
              >
                ◀
              </button>
              <div className="flex gap-2">
                {features.map((_, i) => (
                  <span
                    key={i}
                    className={`w-2 h-2 rounded-full transition-all ${
                      i === currentIndex ? "bg-white" : "bg-gray-600"
                    }`}
                  />
                ))}
              </div>
              <button
                onClick={next}
                className="p-2 text-white hover:scale-110 transition-transform"
              >
                ▶
              </button>
            </div>
          </div>

          {/* Desktop: Stacked */}
          <div className="hidden sm:block space-y-8 text-left">
            {features.map((f, i) => (
              <Feature key={i} {...f} />
            ))}
          </div>

          {/* Subscribe Button */}
          <button className="hidden md:inline relative px-6 py-2 rounded-full bg-gradient-to-r from-pink-500 to-blue-500 text-white font-medium hover:scale-105 transition-transform">
            Subscribe
          </button>
        </div>
      </div>
    </section>
  );
};

const Feature = ({ title, text }: { title: string; text: string }) => (
  <div className="space-y-2">
    <h3 className="text-lg sm:text-xl font-semibold text-white">{title}</h3>
    <p className="text-gray-300 text-sm sm:text-base leading-relaxed">{text}</p>
  </div>
);


const features = [
  {
    title: "WE CARE",
    text: "We care about our work. We care about doing a good job. We care about customers. We care about the environment and society. We are not ‘just doing our job’ — we care about the product you receive and the experience you will have.",
  },
  {
    title: "WE PROVIDE THE BEST QUALITY",
    text: "Here you will find products of the best brands in gadgets world. Moreover, if you are not satisfied with the quality of a product, we are always here to help you.",
  },
  {
    title: "WE PROMOTE THE COMFORT",
    text: "We deliver all products to provide the most comfortable service. You can order a delivery to home, office, garage, garden, island... wherever you need.",
  },
  {
    title: "WE LEARN",
    text: "We always grow and learn new things. We have a special blog about news from the tech world. Subscribe to receive news and articles which our specialists recommend to read to keep up with the fast-growing world of tech.",
  },
];




export default About