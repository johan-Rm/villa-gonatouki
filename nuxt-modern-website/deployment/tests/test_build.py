"""Exercise deployment in a temporary filesystem with simulated Yarn/chmod."""
import os
from pathlib import Path
import subprocess
import tempfile
import unittest

SCRIPT = Path(__file__).resolve().parents[1] / 'build.sh'


class BuildTest(unittest.TestCase):
    def run_build(self, *, stub=False, fail_generate=False, fail_copy=False):
        with tempfile.TemporaryDirectory() as directory:
            root = Path(directory)
            project = root / 'project'
            for name in ('deployment', 'dist', 'update/nuxt_routes', 'node_modules/.cache'):
                (project / name).mkdir(parents=True)
            (project / 'dist/old.html').write_text('old')
            (project / 'update/nuxt_routes/route').write_text('cached')
            if stub:
                (project / 'deployment/robots.txt').write_text('robots')
            bins = root / 'bin'
            bins.mkdir()
            commands = {
                'yarn': 'exit 1' if fail_generate else 'mkdir -p dist_tmp; echo new > dist_tmp/index.html',
                'chmod': 'exit 0',
                'chown': 'exit 99',
            }
            if fail_copy:
                commands['cp'] = 'exit 1'
            for name, body in commands.items():
                command = bins / name
                command.write_text('#!/bin/sh\n' + body + '\n')
                command.chmod(0o755)
            script = root / 'build.sh'
            script.write_text(SCRIPT.read_text().replace(
                '/var/www/villa-gonatouki/nuxt-modern-website', str(project)
            ).replace('/var/www/resources/villa-gonatouki', str(root / 'resources')))
            result = subprocess.run(['sh', str(script)], env={
                **os.environ, 'PATH': str(bins) + ':' + os.environ['PATH']
            }, capture_output=True, text=True)
            if fail_generate or fail_copy:
                self.assertNotEqual(result.returncode, 0, result.stdout)
                self.assertEqual((project / 'dist/old.html').read_text(), 'old')
            else:
                self.assertEqual(result.returncode, 0, result.stderr)
                self.assertTrue((project / 'dist/index.html').exists())
                self.assertFalse((project / 'dist/old.html').exists())
                self.assertFalse((project / 'update/nuxt_routes/route').exists())
                if stub:
                    self.assertEqual((project / 'dist/robots.txt').read_text(), 'robots')
                    self.assertTrue((project / 'deployment/robots.txt').exists())

    def test_absent_optional_stubs(self):
        self.run_build()

    def test_copies_existing_stub_without_moving_source(self):
        self.run_build(stub=True)

    def test_failed_generation_preserves_previous_site(self):
        self.run_build(fail_generate=True)

    def test_failed_stub_copy_preserves_previous_site(self):
        self.run_build(stub=True, fail_copy=True)


if __name__ == '__main__':
    unittest.main()
