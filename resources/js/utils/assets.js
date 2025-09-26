// Assets utility for managing images and fonts
export const assets = {
  // Images
  images: {
    hero: '/assets/img/hero-section-student.png',
    about: '/assets/img/about-us.jpg',
    macbook: '/assets/public/macbook.png',
    airpods: '/assets/public/airpods-max.png',
    vision: '/assets/public/apple-vision.png',
    logitech: '/assets/public/logitech.png',
    gameArm: '/assets/public/game-arm.png',
    solo3: '/assets/public/solo3.png',
    apps: '/assets/public/apps.png',
    npc: '/assets/public/npc.png',
    npcRandom: '/assets/public/npc-random.png',
    pp: '/assets/public/pp.png',
    // Avatars
    avatarMale: '/assets/img/avatar-male.jpeg',
    avatarFemale: '/assets/img/avatar-female.jpeg',
    // Undraw illustrations
    undrawPosting: '/assets/img/undraw_posting_photo.svg',
    undrawProfile: '/assets/img/undraw_profile.svg',
    undrawProfile1: '/assets/img/undraw_profile_1.svg',
    undrawProfile2: '/assets/img/undraw_profile_2.svg',
    undrawProfile3: '/assets/img/undraw_profile_3.svg',
    undrawRocket: '/assets/img/undraw_rocket.svg',
  },

  // Fonts
  fonts: {
    sfProRegular: '/assets/public/fonts/SFPRODISPLAYREGULAR.OTF',
    sfProMedium: '/assets/public/fonts/SFPRODISPLAYMEDIUM.OTF',
    sfProBold: '/assets/public/fonts/SFPRODISPLAYBOLD.OTF',
  },

  // Helper function to get image URL
  getImage: (imageName) => {
    return assets.images[imageName] || '';
  },

  // Helper function to get font URL
  getFont: (fontName) => {
    return assets.fonts[fontName] || '';
  }
};

// Export individual assets for easier imports
export const {
  images,
  fonts,
  getImage,
  getFont
} = assets;

export default assets;
