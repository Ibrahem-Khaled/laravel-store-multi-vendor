import { motion } from "framer-motion";
import { useState } from "react";


function ProductCarousel() {
  const [index, setIndex] = useState(1);

  const renderStars = (rating: number) =>
    Array.from({ length: 5 }, (_, i) => (
      <span key={i} className={`text-lg ${i < rating ? "text-black" : "text-gray-400"}`}>
        ★
      </span>
    ));

  return (
    <div className="w-full overflow-x-clip flex flex-col items-center -mt-[70px] lg:-mt-[130px]">
      {/* DESKTOP / TABLET */}
      <div className="hidden sm:grid grid-cols-3 gap-6 max-w-[90vw] mx-auto">
        {products.map((p) => (
          <div
            key={p.id}
            className="relative w-[300px] h-[320px] sm:w-[200px] sm:h-[320px] lg:w-[320px]  lg:h-[360px] rounded-3xl overflow-hidden backdrop-blur-xl bg-gray-300/50"
          >
            <div className="flex flex-col p-6 h-full justify-between">
              <div className="flex justify-center items-center bg-gray-700/30 h-32 rounded-xl">
                <img src={p.image} alt={p.title} className="h-32 object-contain" />
              </div>
              <div>
                <h3 className="text-sm lg:text-lg font-medium text-black">{p.title}</h3>
                <div className="flex items-center gap-2 text-[11px] lg:text-sm">
                  {p.oldPrice && <span className="line-through text-red-600">{p.oldPrice}$</span>}
                  <span className="text-black font-semibold">{p.price}$</span>
                </div>
              </div>
              <div className="flex gap-1">{renderStars(p.rating)}</div>
              <div className="flex gap-2">
                {p.colors.map((c, i) => (
                  <span key={i} className={`w-5 h-5 rounded-md border border-gray-300 ${c}`} />
                ))}
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* MOBILE CAROUSEL */}
      <div className="sm:hidden w-full max-w-screen relative overflow-scroll">
        <motion.div
          className="flex justify-center items-center"
          animate={{ x: `-${index * 3}vw` }} // moves fixed card width
          transition={{ type: "spring", stiffness: 200, damping: 25 }}
        >
          {products.map((p, i) => (
            <div key={i} className="shrink-0 w-[210px] mx-3">
              <div className="h-[300px] rounded-2xl overflow-hidden backdrop-blur-md bg-white/30 shadow-md p-5 flex flex-col justify-between">
                <div className="flex justify-center items-center bg-gray-700/20 h-28 rounded-xl">
                  <img src={p.image} alt={p.title} className="h-28 object-contain" />
                </div>
                <div>
                  <h3 className="text-base font-medium line-clamp-2 text-black">{p.title}</h3>
                  <div className="flex items-center gap-2">
                    {p.oldPrice && <span className="text-xs line-through text-red-600">{p.oldPrice}$</span>}
                    <span className="font-semibold text-sm">{p.price}$</span>
                  </div>
                </div>
                <div className="flex gap-1">{renderStars(p.rating)}</div>
                <div className="flex gap-2">
                  {p.colors.map((c, i) => (
                    <span key={i} className={`w-4 h-4 rounded-md border border-gray-300 ${c}`} />
                  ))}
                </div>
              </div>
            </div>
          ))}
        </motion.div>

        {/* Dots */}
        <div className="flex justify-center gap-2 mt-3">
          {products.map((_, i) => (
            <button
              key={i}
              onClick={() => setIndex(i)}
              className={`w-2.5 h-2.5 rounded-full transition-all ${
                i === index ? "bg-gray-800 scale-110" : "bg-gray-300"
              }`}
            />
          ))}
        </div>
      </div>
    </div>
  );
}


export default ProductCarousel


const products = [
  {
    id: 1,
    title: "Beats Solo3 Wireless Headphones",
    price: 213,
    oldPrice: 300,
    rating: 5,
    colors: ["bg-gray-800", "bg-white", "bg-orange-500", "bg-red-600"],
    image: "/solo3.png",
  },
  {
    id: 2,
    title: "Airpods Max",
    price: 400,
    oldPrice: 479.3,
    rating: 4,
    colors: ["bg-gray-300", "bg-blue-300", "bg-gray-400", "bg-green-200"],
    image: "/airpods-max.png",
  },
  {
    id: 3,
    title: "Logitech G733 Gaming Headphone",
    price: 173.9,
    oldPrice: 243.3,
    rating: 5,
    colors: ["bg-black", "bg-purple-700", "bg-orange-400"],
    image: "/logitech.png",
  },
];
