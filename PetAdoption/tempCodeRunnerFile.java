public static int mystery (int z, int n) {
if (n==z)
return z;
else if (n > z)
return mystery (z, n-z);
else
return mystery (z-n, n);