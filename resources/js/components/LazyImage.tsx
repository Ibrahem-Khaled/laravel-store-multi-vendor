import React, { useState, useRef } from "react";
import { motion } from "framer-motion";
import LoadingSpinner from "./LoadingSpinner";

interface LazyImageProps {
  src: string;
  alt: string;
  className?: string;
  initial?: any;
  animate?: any;
  transition?: any;
  whileInView?: any;
  viewport?: any;
  whileHover?: any;
  [key: string]: any;
}

const LazyImage: React.FC<LazyImageProps> = ({
  src,
  alt,
  className = "",
  initial,
  animate,
  transition,
  whileInView,
  viewport,
  whileHover,
  ...motionProps
}) => {
  const [isLoaded, setIsLoaded] = useState(false);
  const [hasError, setHasError] = useState(false);
  const imgRef = useRef<HTMLImageElement>(null);

  const handleLoad = () => {
    setIsLoaded(true);
  };

  const handleError = () => {
    setHasError(true);
    setIsLoaded(true);
  };

  return (
    <div className="relative">
      {!isLoaded && !hasError && (
        <div className="absolute inset-0 flex items-center justify-center bg-gray-100 rounded">
          <LoadingSpinner size="md" color="text-blue-600" />
        </div>
      )}

      <motion.img
        ref={imgRef}
        src={src}
        alt={alt}
        loading="lazy"
        decoding="async"
        onLoad={handleLoad}
        onError={handleError}
        className={`${className} ${!isLoaded ? "opacity-0" : "opacity-100"} transition-opacity duration-300`}
        initial={initial}
        animate={animate}
        transition={transition}
        whileInView={whileInView}
        viewport={viewport}
        whileHover={whileHover}
        {...motionProps}
      />

      {hasError && isLoaded && (
        <div className="absolute inset-0 flex items-center justify-center bg-gray-200 rounded text-gray-500">
          <span className="text-sm">فشل في تحميل الصورة</span>
        </div>
      )}
    </div>
  );
};

export default LazyImage;
